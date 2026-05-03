<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Occupation;
use App\Models\Program;
use App\Models\AuditLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    private function baseQuery(Request $request)
    {
        $query = Student::query()
            ->leftJoin('districts', 'students.district_id', '=', 'districts.id')
            ->leftJoin('upazilas', 'students.upajila_id', '=', 'upazilas.id')
            ->leftJoin('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->leftJoin('programs', 'students.program_id', '=', 'programs.id')
            ->leftJoin('insatitutes', 'students.institutionName', '=', 'insatitutes.id')
            ->select(
                'students.id',
                'students.candidate_name',
                'students.candidate_name_bn',
                'students.father_name',
                'students.father_name_bn',
                'students.mother_name',
                'students.mother_name_bn',
                'students.registration_number',
                'students.candidate_id',
                'students.nid',
                'students.brn',
                'students.gender',
                'students.exam_status',
                'students.status',
                'students.certificate_number',
                'students.date_of_birth',
                'students.assessment_date',
                'districts.name_en as district_name',
                'upazilas.name_en as upazila_name',
                'occupations.title as occupation_title',
                'programs.program_title',
                'insatitutes.insatitute_name'
            );

        // Role-based district filter
        if (!can('chairman') && can('district_admin')) {
            $query->where('students.district_id', auth()->user()->district_id);
        }

        // Request filters
        if ($request->filled('district_id'))   $query->where('students.district_id', $request->district_id);
        if ($request->filled('upazila_id'))    $query->where('students.upajila_id', $request->upazila_id);
        if ($request->filled('program_id'))    $query->where('students.program_id', $request->program_id);
        if ($request->filled('occupation_id')) $query->where('students.occupation_id', $request->occupation_id);
        if ($request->filled('gender'))        $query->where('students.gender', $request->gender);
        if ($request->filled('exam_status'))   $query->where('students.exam_status', $request->exam_status);
        if ($request->filled('date_from'))     $query->whereDate('students.assessment_date', '>=', $request->date_from);
        if ($request->filled('date_to'))       $query->whereDate('students.assessment_date', '<=', $request->date_to);
        if ($request->filled('search'))        $query->where(function($q) use ($request) {
            $q->where('students.candidate_name', 'like', '%'.$request->search.'%')
              ->orWhere('students.registration_number', 'like', '%'.$request->search.'%')
              ->orWhere('students.candidate_id', 'like', '%'.$request->search.'%');
        });

        return $query;
    }

    private function filterData()
    {
        $districtsQuery = District::query();
        if (!can('chairman') && can('district_admin')) {
            $districtsQuery->where('id', auth()->user()->district_id);
        }
        return [
            'districts'   => $districtsQuery->pluck('name_en', 'id')->prepend('All Districts', ''),
            'upazilas'    => Upazila::pluck('name_en', 'id')->prepend('All Upazilas', ''),
            'occupations' => Occupation::pluck('title', 'id')->prepend('All Occupations', ''),
            'programs'    => Program::pluck('program_title', 'id')->prepend('All Programs', ''),
        ];
    }

    public function index()
    {
        return view('reports.index');
    }

    public function projectWise(Request $request)
    {
        $students = $this->baseQuery($request)->orderBy('programs.program_title')->orderBy('students.id')->paginate(50)->withQueryString();
        $filters  = $this->filterData();
        $title    = 'Project-wise Report';
        $type     = 'project_wise';
        return view('reports.table', compact('students', 'filters', 'title', 'type', 'request'));
    }

    public function districtWise(Request $request)
    {
        $students = $this->baseQuery($request)->orderBy('districts.name_en')->orderBy('students.id')->paginate(50)->withQueryString();
        $filters  = $this->filterData();
        $title    = 'District-wise Report';
        $type     = 'district_wise';
        return view('reports.table', compact('students', 'filters', 'title', 'type', 'request'));
    }

    public function upazilaWise(Request $request)
    {
        $students = $this->baseQuery($request)->orderBy('upazilas.name_en')->orderBy('students.id')->paginate(50)->withQueryString();
        $filters  = $this->filterData();
        $title    = 'Upazila-wise Report';
        $type     = 'upazila_wise';
        return view('reports.table', compact('students', 'filters', 'title', 'type', 'request'));
    }

    public function genderWise(Request $request)
    {
        $students = $this->baseQuery($request)->orderBy('students.gender')->orderBy('students.id')->paginate(50)->withQueryString();
        $filters  = $this->filterData();
        $title    = 'Gender-wise Report';
        $type     = 'gender_wise';
        return view('reports.table', compact('students', 'filters', 'title', 'type', 'request'));
    }

    public function occupationWise(Request $request)
    {
        $students = $this->baseQuery($request)->orderBy('occupations.title')->orderBy('students.id')->paginate(50)->withQueryString();
        $filters  = $this->filterData();
        $title    = 'Occupation-wise Report';
        $type     = 'occupation_wise';
        return view('reports.table', compact('students', 'filters', 'title', 'type', 'request'));
    }

    public function studentId(Request $request)
    {
        $students = $this->baseQuery($request)->orderBy('students.id')->paginate(50)->withQueryString();
        $filters  = $this->filterData();
        $title    = 'Student ID-based Report';
        $type     = 'student_id';
        return view('reports.table', compact('students', 'filters', 'title', 'type', 'request'));
    }

    public function certificateDistribution(Request $request)
    {
        $students = $this->baseQuery($request)
            ->where('students.status', 'Chairman Approved')
            ->whereNotNull('students.certificate_number')
            ->orderBy('students.id')
            ->paginate(50)->withQueryString();
        $filters  = $this->filterData();
        $title    = 'Certificate Distribution Report';
        $type     = 'certificate_distribution';
        return view('reports.table', compact('students', 'filters', 'title', 'type', 'request'));
    }

    public function nycStudents(Request $request)
    {
        $students = $this->baseQuery($request)
            ->where('students.exam_status', 'Fail')
            ->orderBy('students.id')
            ->paginate(50)->withQueryString();
        $filters  = $this->filterData();
        $title    = 'Failed / NYC Students Report';
        $type     = 'nyc_students';
        return view('reports.table', compact('students', 'filters', 'title', 'type', 'request'));
    }

    public function exportExcel(Request $request)
    {
        AuditLog::log('report.exported', Student::class, null, [], ['type' => $request->type ?? 'excel'], 'Excel report exported');
        $filename = 'report_' . ($request->type ?? 'general') . '_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new ReportExport($this->typedQuery($request)->get()), $filename);
    }

    public function exportPdf(Request $request)
    {
        AuditLog::log('report.exported', Student::class, null, [], ['type' => $request->type ?? 'pdf'], 'PDF report exported');
        $students = $this->typedQuery($request)->get();
        $title    = $request->input('title', 'Report');
        $pdf      = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.report_pdf', compact('students', 'title'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('report_' . now()->format('Ymd_His') . '.pdf');
    }

    /** Apply type-specific WHERE filters on top of baseQuery for exports */
    private function typedQuery(Request $request)
    {
        $q = $this->baseQuery($request);
        switch ($request->type) {
            case 'certificate_distribution':
                $q->where('students.status', 'Chairman Approved')
                  ->whereNotNull('students.certificate_number');
                break;
            case 'nyc_students':
                $q->where('students.exam_status', 'Fail');
                break;
        }
        return $q;
    }
}
