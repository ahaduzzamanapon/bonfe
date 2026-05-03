<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Reassessment;
use App\Models\AssessmentCenter;
use App\Models\AuditLog;
use App\Models\Competence;
use App\Models\StudentCompetenceModel;

class ReassessmentController extends Controller
{
    /** List all NYC students + their re-assessment applications */
    public function index(Request $request)
    {
        $query = Student::where('exam_status', 'Fail')
            ->with(['reassessments' => fn($q) => $q->latest()])
            ->orderBy('id', 'desc');

        if (!can('chairman') && can('district_admin')) {
            $query->where('district_id', auth()->user()->district_id);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('candidate_name', 'like', '%'.$request->search.'%')
                  ->orWhere('registration_number', 'like', '%'.$request->search.'%');
            });
        }

        $students = $query->paginate(30)->withQueryString();
        $centers  = AssessmentCenter::pluck('center_name', 'id')->prepend('Select Center', '');
        return view('reassessments.index', compact('students', 'centers'));
    }

    /** Submit a re-assessment application for a student */
    public function apply(Request $request)
    {
        $request->validate(['student_id' => 'required|exists:students,id', 'reason' => 'nullable|string']);

        $student = Student::findOrFail($request->student_id);
        $attempt = Reassessment::where('student_id', $student->id)->count() + 1;

        $ra = Reassessment::create([
            'student_id'       => $student->id,
            'attempt_number'   => $attempt,
            'application_date' => now()->toDateString(),
            'reason'           => $request->reason,
            'status'           => Reassessment::STATUS_PENDING,
        ]);

        AuditLog::log('reassessment.applied', Reassessment::class, $ra->id, [], $ra->toArray(), "Re-assessment applied for student #{$student->id}");

        return response()->json(['success' => true, 'message' => 'Re-assessment application submitted.']);
    }

    /** Schedule a re-assessment (Assessment Controller) */
    public function schedule(Request $request)
    {
        $request->validate([
            'reassessment_id'   => 'required|exists:reassessments,id',
            'scheduled_date'    => 'required|date',
            'scheduled_center_id' => 'required|exists:assessment_centers,id',
        ]);

        $ra = Reassessment::findOrFail($request->reassessment_id);
        $ra->update([
            'scheduled_date'     => $request->scheduled_date,
            'scheduled_center_id'=> $request->scheduled_center_id,
            'status'             => Reassessment::STATUS_SCHEDULED,
        ]);

        AuditLog::log('reassessment.scheduled', Reassessment::class, $ra->id, [], $ra->toArray(), "Re-assessment scheduled");

        return response()->json(['success' => true, 'message' => 'Re-assessment scheduled.']);
    }

    /** Enter re-assessment result (Assessment Centers Controller) */
    public function enterResult(Request $request)
    {
        $request->validate([
            'reassessment_id' => 'required|exists:reassessments,id',
            'exam_status'     => 'required|in:Passed,Fail,Absent',
        ]);

        $ra = Reassessment::findOrFail($request->reassessment_id);

        $resultSheetPath = null;
        if ($request->hasFile('exam_result_sheet')) {
            $resultSheetPath = uploadFile($request->file('exam_result_sheet'), 'reassessment_results');
        }

        $ra->update([
            'exam_status'      => $request->exam_status,
            'exam_result_sheet'=> $resultSheetPath,
            'status'           => Reassessment::STATUS_WAITING_CONTROLLER,
        ]);

        // Update student exam_status
        $student = $ra->student;
        if ($request->exam_status === 'Passed') {
            $student->exam_status = 'Passed';
            $student->save();
            // Mark all competencies as passed
            $competences = Competence::where('occupation_id', $student->occupation_id)->get();
            StudentCompetenceModel::where('student_id', $student->id)->delete();
            foreach ($competences as $comp) {
                StudentCompetenceModel::create(['student_id' => $student->id, 'competence_id' => $comp->id]);
            }
        }

        AuditLog::log('reassessment.result_entered', Reassessment::class, $ra->id, [], $ra->toArray(), "Re-assessment result entered: {$request->exam_status}");

        return response()->json(['success' => true, 'message' => 'Result entered successfully.']);
    }

    /** Chairman bulk approve */
    public function chairmanApprove(Request $request)
    {
        $request->validate(['reassessment_ids' => 'required|array']);

        $approved = 0;
        foreach ($request->reassessment_ids as $id) {
            $ra = Reassessment::find($id);
            if (!$ra) continue;
            $ra->update([
                'status'              => Reassessment::STATUS_APPROVED,
                'chairman_id'         => auth()->id(),
                'chairman_approved_at'=> now(),
            ]);
            // Update student status to Chairman Approved
            $student = $ra->student;
            if ($student && $ra->exam_status === 'Passed') {
                $student->status = 'Chairman Approved';
                $student->chairmen_status = 'Approved';
                $student->chairmen_id = auth()->id();
                $student->save();
            }
            AuditLog::log('reassessment.chairman_approved', Reassessment::class, $ra->id, [], [], "Re-assessment chairman approved");
            $approved++;
        }

        return response()->json(['success' => true, 'message' => "$approved re-assessment(s) approved."]);
    }

    /** Generate re-assessment certificate */
    public function generateCertificate($id)
    {
        $ra      = Reassessment::with('student')->findOrFail($id);
        $student = $ra->student;
        if ($student->exam_status !== 'Passed') {
            abort(403, 'Student has not passed the re-assessment.');
        }
        AuditLog::log('reassessment.certificate_generated', Student::class, $student->id, [], [], "Re-assessment certificate generated");
        return view('reassessments.certificate.pass', compact('student', 'ra'));
    }
}
