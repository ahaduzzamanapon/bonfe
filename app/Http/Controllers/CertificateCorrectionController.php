<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\CertificateCorrection;
use App\Models\CertificateVersion;
use App\Models\AuditLog;

class CertificateCorrectionController extends Controller
{
    /** List all correction applications */
    public function index(Request $request)
    {
        $query = CertificateCorrection::with('student')
            ->orderBy('id', 'desc');

        if (!can('chairman') && can('district_admin')) {
            $studentIds = Student::where('district_id', auth()->user()->district_id)->pluck('id');
            $query->whereIn('student_id', $studentIds);
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) => $q->where('candidate_name', 'like', '%'.$request->search.'%')
                ->orWhere('registration_number', 'like', '%'.$request->search.'%'));
        }

        $corrections = $query->paginate(20)->withQueryString();
        return view('certificate_corrections.index', compact('corrections'));
    }

    /** Application form */
    public function create($studentId)
    {
        $student = Student::findOrFail($studentId);
        $correctableFields = [
            'candidate_name'    => "Candidate Name (English)",
            'candidate_name_bn' => "Candidate Name (Bangla)",
            'father_name'       => "Father's Name (English)",
            'father_name_bn'    => "Father's Name (Bangla)",
            'mother_name'       => "Mother's Name (English)",
            'mother_name_bn'    => "Mother's Name (Bangla)",
            'date_of_birth'     => "Date of Birth",
            'nid'               => "NID Number",
            'brn'               => "Birth Registration Number",
            'certificate_number'=> "Certificate Number",
        ];
        return view('certificate_corrections.create', compact('student', 'correctableFields'));
    }

    /** Store correction application */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'       => 'required|exists:students,id',
            'reason'           => 'required|string',
            'correction_fields'=> 'required|array|min:1',
        ]);

        $student = Student::findOrFail($request->student_id);

        // Upload supporting documents
        $docPaths = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $docPaths[] = uploadFile($file, 'correction_documents');
            }
        }

        // Build correction_fields diff
        $correctionFields = [];
        foreach ($request->correction_fields as $field => $newValue) {
            if (!empty($newValue) && $newValue !== $student->$field) {
                $correctionFields[$field] = ['old' => $student->$field, 'new' => $newValue];
            }
        }

        if (empty($correctionFields)) {
            return back()->withErrors(['correction_fields' => 'No changes detected. Please enter new values that differ from the current ones.']);
        }

        // Archive current certificate as version 1
        $currentVersion = CertificateVersion::where('student_id', $student->id)->count();
        if ($currentVersion === 0) {
            CertificateVersion::create([
                'student_id'    => $student->id,
                'correction_id' => null,
                'version'       => 1,
                'snapshot_data' => $student->toArray(),
                'issued_at'     => now(),
                'issued_by'     => auth()->id(),
            ]);
        }

        $correction = CertificateCorrection::create([
            'student_id'          => $student->id,
            'application_date'    => now()->toDateString(),
            'correction_fields'   => $correctionFields,
            'reason'              => $request->reason,
            'supporting_documents'=> $docPaths,
            'status'              => CertificateCorrection::STATUS_PENDING,
        ]);

        AuditLog::log('certificate_correction.applied', CertificateCorrection::class, $correction->id, [], $correction->toArray(), "Certificate correction applied for student #{$student->id}");

        return redirect()->route('certificate_corrections.index')->with('success', 'Correction application submitted successfully.');
    }

    /** View single application */
    public function show($id)
    {
        $correction = CertificateCorrection::with('student', 'controller', 'chairman')->findOrFail($id);
        return view('certificate_corrections.show', compact('correction'));
    }

    /** Assessment Controller approve/reject */
    public function controllerApprove(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:approve,reject', 'remarks' => 'nullable|string']);

        $correction = CertificateCorrection::findOrFail($id);
        $newStatus  = $request->action === 'approve' ? CertificateCorrection::STATUS_CONTROLLER_APPROVED : CertificateCorrection::STATUS_REJECTED;

        $correction->update([
            'status'              => $newStatus,
            'controller_id'       => auth()->id(),
            'controller_remarks'  => $request->remarks,
            'controller_approved_at' => now(),
        ]);

        AuditLog::log('certificate_correction.controller_' . $request->action, CertificateCorrection::class, $correction->id, [], [], "Certificate correction {$request->action}d by controller");

        return response()->json(['success' => true, 'message' => "Correction {$request->action}d by controller."]);
    }

    /** Chairman approve/reject + regenerate certificate */
    public function chairmanApprove(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:approve,reject', 'remarks' => 'nullable|string']);

        $correction = CertificateCorrection::findOrFail($id);

        if ($request->action === 'reject') {
            $correction->update([
                'status'               => CertificateCorrection::STATUS_REJECTED,
                'chairman_id'          => auth()->id(),
                'chairman_remarks'     => $request->remarks,
                'chairman_approved_at' => now(),
            ]);
            return response()->json(['success' => true, 'message' => 'Correction rejected.']);
        }

        // Approve: apply corrections to student record
        $student = $correction->student;
        $oldData = $student->toArray();

        foreach ($correction->correction_fields as $field => $diff) {
            $student->$field = $diff['new'] ?? $student->$field;
        }
        $student->save();

        $correction->update([
            'status'               => CertificateCorrection::STATUS_CHAIRMAN_APPROVED,
            'chairman_id'          => auth()->id(),
            'chairman_remarks'     => $request->remarks,
            'chairman_approved_at' => now(),
        ]);

        // Archive new version
        $nextVersion = CertificateVersion::where('student_id', $student->id)->max('version') + 1;
        CertificateVersion::create([
            'student_id'    => $student->id,
            'correction_id' => $correction->id,
            'version'       => $nextVersion,
            'snapshot_data' => $student->fresh()->toArray(),
            'issued_at'     => now(),
            'issued_by'     => auth()->id(),
        ]);

        AuditLog::log('certificate_correction.chairman_approved', CertificateCorrection::class, $correction->id, $oldData, $student->toArray(), "Certificate correction chairman approved — student data updated");

        return response()->json(['success' => true, 'message' => 'Correction approved and certificate updated.']);
    }

    /** Show all certificate versions for a student */
    public function versions($studentId)
    {
        $student  = Student::findOrFail($studentId);
        $versions = CertificateVersion::where('student_id', $studentId)->with('correction', 'issuer')->orderBy('version')->get();
        return view('certificate_corrections.versions', compact('student', 'versions'));
    }
}
