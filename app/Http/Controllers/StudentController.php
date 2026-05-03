<?php

namespace App\Http\Controllers;
use App\Models\StudentCompetenceModel;
use App\Models\AssessmentCenter;
use App\Models\Competence;
use App\Models\Student;
use App\Models\Upazila;
use App\Models\Occupation;
use App\Models\Insatitute;
use App\Models\District;
use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Form;
use DB;
use DateTime;


use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelDataImport;
use App\Models\AuditLog;




class StudentController extends AppBaseController
{
    /**
     * Display a listing of the Student.
     *
     * @param Request $request
     *
     * @return Response
     */



    public function testApi()
    {
        $sampleData = [
            [
                'id' => 1,
                'name' => 'Item A',
                'description' => 'This is the first item.',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Item B',
                'description' => 'This is the second item.',
                'status' => 'inactive'
            ],
            [
                'id' => 3,
                'name' => 'Item C',
                'description' => 'This is the third item.',
                'status' => 'pending'
            ]
        ];
        return response()->json($sampleData, 200);
    }

    public function getStudentsjson()
    {

        $students = DB::table('students')
            ->select(
                'id',
                'registration_number',
                'candidate_id',
                'candidate_name as candidate_name_en',
                'candidate_name_bn',
                'father_name',
                'mother_name'
            )
            ->get();

        return response()->json($students);
    }
    public function index(Request $request)
    {
        return view('students.index');
    }
    public function students_waiting_for_district_approval(Request $request)
    {
        return view('students.index');
    }
    public function students_waiting_for_chairman_approval(Request $request)
    {
        return view('students.index');
    }
    public function students_back_to_district_approval(Request $request)
    {
        return view('students.index');
    }
    public function get_table(Request $request)
    {
        $limit = $request->input('limit', 50); // default 50
        $offset = $request->input('offset', 0);
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->join('programs', 'students.program_id', '=', 'programs.id')
            ->orderBy('id', 'desc');
        if (!can('chairman') && can('district_admin')) {
            $students = $students->where('students.district_id', auth()->user()->district_id);
        }

        if (can('assessment_centers_controller')) {
            $students = $students->where('students.assessment_center', auth()->user()->assessment_center);
            $students = $students->where('students.occupation_id', auth()->user()->occupation);
        }
        if ($request->has('status_filter') && $request->status_filter != 'all') {
            if ($request->status_filter == 'waiting_for_district_approval') {
                $students = $students->where('students.status', 'Waiting for District Admin Approval');
            } elseif ($request->status_filter == 'waiting_for_chairman_approval') {
                $students = $students->where('students.status', 'Waiting for Chairman Approval');
            } elseif ($request->status_filter == 'waiting_for_assessment_center_approval') {
                $students = $students->where('students.status', 'Waiting for the exam results from the Assessment Center');
            } elseif ($request->status_filter == 'back_to_district_approval') {
                $students = $students->where('students.status', 'Waiting for District Admin Approval');
                $students = $students->whereNotNull('students.controller_back_comments');
            } elseif ($request->status_filter == 'waiting_for_registration') {
                $students = $students->where('students.status', 'Waiting for Registration');
            }

        }
        if ($request->has('occupation_id') && $request->occupation_id != null) {
            $students = $students->where('students.occupation_id', $request->occupation_id);
        }
        if ($request->has('program_id') && $request->program_id != null && $request->program_id != '') {
            $students = $students->where('students.program_id', $request->program_id);
        }


        if ($request->has('district_id') && $request->district_id != null && $request->district_id != '') {
            $students = $students->where('students.district_id', $request->district_id);
        }
        if ($request->has('upajila_id') && $request->upajila_id != null && $request->upajila_id != '') {
            $students = $students->where('students.upajila_id', $request->upajila_id);
        }
        if ($request->has('search_term') && $request->search_term != null && $request->search_term != '') {
            $students = $students->where(function ($query) use ($request) {
                $query->where('students.candidate_name', 'like', '%' . $request->search_term . '%')
                    ->orWhere('students.registration_number', 'like', '%' . $request->search_term . '%')
                    ->orWhere('students.candidate_name_bn', 'like', '%' . $request->search_term . '%')
                    ->orWhere('students.certificate_number', 'like', '%' . $request->search_term . '%');
            });
        }


        if ($request->has('program_type') && $request->program_type == 'General') {
            $students = $students->where('programs.program_type', 'General');
        } else {
            $students = $students->where('programs.program_type', 'Technical');
        }







        $total = $students->count();
        $students = $students->offset($offset)->limit($limit)->get();



        return response()->json([
            'success' => true,
            'students' => $students,
            'total' => $total
        ]);
    }
    /**
     * Show the form for creating a new Student.
     *
     * @return Response
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created Student in storage.
     *
     * @param CreateStudentRequest $request
     *
     * @return Response
     */
    public function get_candidate_number_preview(Request $request)
    {
        $instituteId = $request->institutionName;
        $occupationId = $request->occupation_id;
        $districtId = $request->district_id;

        $type = 'empty';
        $tradeCode = 'empty';
        $distCode = 'empty';
        $instCode = 'empty';
        $serial = '0001';

        if ($instituteId) {
            $institute = Insatitute::find($instituteId);
            if ($institute) {
                $type = $institute->type ?? 'empty';
                $instCode = $institute->code ?? 'empty';
            }
        }

        if ($occupationId) {
            $occupation = Occupation::find($occupationId);
            if ($occupation) {
                $tradeCode = $occupation->code ?? 'empty';
            }
        }

        if ($districtId) {
            $district = District::find($districtId);
            if ($district) {
                $distCode = $district->code ?? 'empty';
            }
        }

        // Count existing students with this exact combination to determine serial
        if ($instituteId && $occupationId && $districtId) {
            $query = Student::where('district_id', $districtId)
                ->where('occupation_id', $occupationId)
                ->where('institutionName', $instituteId);

            if ($request->has('program_id')) {
                $query->where('program_id', $request->program_id);
            }

            $count = $query->count();

            $serial = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        }

        $candidateId = "{$type}-{$tradeCode}-{$distCode}-{$instCode}-{$serial}";

        return response()->json(['candidate_id' => $candidateId]);
    }

    /**
     * Store a newly created Student in storage.
     *
     * @param CreateStudentRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $input = $request->all();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'images/student';
            $customName = 'image-' . time();
            $input['image'] = uploadFile($file, $folder, $customName);
        } else {
            $input['image'] = 'no-image.png';
        }


        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $folder = 'images/attachment';
            $customName = 'attachment-' . time();
            $input['attachment'] = uploadFile($file, $folder, $customName);
        } else {
            $input['attachment'] = 'no-image.png';
        }

        // Generate Candidate Number
        $instituteId = $input['institutionName'] ?? null;
        $occupationId = $input['occupation_id'] ?? null;
        $districtId = $input['district_id'] ?? null;

        if ($instituteId && $occupationId && $districtId) {
            $institute = Insatitute::find($instituteId);
            $occupation = Occupation::find($occupationId);
            $district = District::find($districtId);

            if ($institute && $occupation && $district) {
                $type = $institute->type ?? 'XXX';
                $tradeCode = $occupation->code ?? 'XXX';
                $distCode = $district->code ?? 'XX';
                $instCode = $institute->code ?? 'XXXX';

                $query = Student::where('district_id', $districtId)
                    ->where('occupation_id', $occupationId)
                    ->where('institutionName', $instituteId);

                if (isset($input['program_id'])) {
                    $query->where('program_id', $input['program_id']);
                }

                $count = $query->count();

                $serial = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
                $input['candidate_id'] = "{$type}-{$tradeCode}-{$distCode}-{$instCode}-{$serial}"; // Override or Set
                // Assuming registration_number is same or different? 
                // User requirement said "Candidate Number". Field is candidate_id.
            }
        }


        $student_type = $input['student_type'];
        unset($input['student_type']);







        /** @var Student $student */
        $student = Student::create($input);

        Flash::success('Student saved successfully.');

        if ($student_type == 'general') {
            return redirect(route('general_students.index'));
        } else {
            return redirect(route('students.index'));
        }
    }

    /**
     * Display the specified Student.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Student $student */
        $student = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->where('students.id', $id)
            ->first();

        if (empty($student)) {
            Flash::error('Student not found');
            if (request()->is('general_students*')) {
                return redirect(route('general_students.index'));
            }
            return redirect(route('students.index'));
        }

        return view('students.show')->with('student', $student);
    }

    /**
     * Show the form for editing the specified Student.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Student $student */
        $student = Student::find($id);


        if (empty($student)) {
            Flash::error('Student not found');
            if (request()->is('general_students*')) {
                return redirect(route('general_students.index'));
            }
            return redirect(route('students.index'));
        }
        // dd($student);

        return view('students.edit')->with('student', $student);
    }

    /**
     * Update the specified Student in storage.
     *
     * @param int $id
     * @param UpdateStudentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStudentRequest $request)
    {


        /** @var Student $student */
        $student = Student::find($id);

        $input = $request->all();


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'images/student';
            $customName = 'image-' . time();
            $input['image'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['image']);
        }


        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $folder = 'images/attachment';
            $customName = 'attachment-' . time();
            $input['attachment'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['attachment']);
        }





        $student->fill($input);
        $student->save();

        Flash::success('Student updated successfully.');

        if (request()->is('general_students*')) {
            return redirect(route('general_students.index'));
        }
        return redirect(route('students.index'));
    }

    /**
     * Remove the specified Student from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Student $student */
        $student = Student::find($id);
        if (empty($student)) {
            Flash::error('Student not found');
            if (request()->is('general_students*')) {
                return redirect(route('general_students.index'));
            }
            return redirect(route('students.index'));
        }
        $student->delete();
        Flash::success('Student deleted successfully.');
        if (request()->is('general_students*')) {
            return redirect(route('general_students.index'));
        } else {
            return redirect(route('students.index'));
        }
    }

    public function submit_exam_result(Request $request)
    {


        if ($request->hasFile('examResultSheet')) {
            $file = $request->file('examResultSheet');
            $folder = 'results/examResultSheet';
            $customName = 'examResultSheet-' . time();
            $input['examResultSheet'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['examResultSheet']);
        }

        $student = Student::find($request->studentId);
        $student->exam_status = $request->examResult;
        $student->exam_result_sheet = $input['examResultSheet'];
        $student->save();
        $checkedCompetences = explode(',', $request->checkedCompetences);

        StudentCompetenceModel::where('student_id', $request->studentId)->delete();

        foreach ($checkedCompetences as $competenceId) {
            $StudentCompetenceModel = new StudentCompetenceModel();
            $StudentCompetenceModel->student_id = $request->studentId;
            $StudentCompetenceModel->competence_id = $competenceId;
            $StudentCompetenceModel->save();
        }


        return response()->json([
            'success' => true,
            'message' => "Result submitted successfully",
            'data' => $student
        ]);
    }
    public function give_candidate_id_submit(Request $request)
    {


        $student = Student::find($request->studentId);

        $students = Student::where('occupation_id', $student->occupation_id)->where('registration_number', $request->candidate_id_field)->get();

        if (count($students) > 0) {
            return response()->json([
                'success' => false,
                'message' => "Registration number already exists in the same occupation",
            ]);
        }

        $student->registration_number = $request->candidate_id_field;
        $student->save();
        return response()->json([
            'success' => true,
            'message' => "Result submitted successfully",
            'data' => $student
        ]);
    }
    public function give_certificate_number_submit(Request $request)
    {


        $student = Student::find($request->studentId);
        $students = Student::where('certificate_number', $request->certificate_number)->get();

        if (count($students) > 0) {
            return response()->json([
                'success' => false,
                'message' => "Certificate number already exists",
            ]);
        }

        $student->certificate_number = $request->certificate_number;
        $student->save();
        AuditLog::log('certificate.number_assigned', Student::class, $student->id, [], ['certificate_number' => $request->certificate_number], 'Certificate number assigned to student: '.$student->candidate_name);
        return response()->json([
            'success' => true,
            'message' => "Result submitted successfully",
            'data' => $student
        ]);
    }
    public function forward_to_chairman($studentId)
    {
        $student = Student::find($studentId);
        $student->districts_admin_status = "Approved";
        $student->districts_admin_id = auth()->user()->id;
        $student->status = 'Waiting for Chairman Approval';
        $student->save();
        Flash::success('Student forwarded to Chairman successfully.');
        return back();
    }
    public function chairman_approve($studentId)
    {
        $student = Student::find($studentId);
        $student->chairmen_status = "Approved";
        $student->chairmen_id = auth()->user()->id;
        $student->status = 'Chairman Approved';
        $student->save();
        Flash::success('Operation completed successfully.');
        $massage = 'প্রিয় ' . $student->candidate_name_bn . ', আপনার প্রশিক্ষণ কোর্সের সার্টিফিকেট প্রস্তুত। অনুগ্রহ করে নির্ধারিত সময়ে আপনার জেলা উপানুষ্ঠানিক শিক্ষা ব্যুরো অফিস থেকে এটি সংগ্রহ করুন।';
        $send = send_sms_new($student->mobile_number, $massage);
        return back();
    }
    public function generate_certificate($studentId)
    {
        $data = route('students.qr_details', $studentId);
        $qrCode = QrCode::size(100)->generate($data);
        $student = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation', 'insatitutes.insatitute_name')
            ->leftJoin('districts', 'students.district_id', '=', 'districts.id')
            ->leftJoin('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->leftJoin('insatitutes', 'students.institutionName', '=', 'insatitutes.id')
            ->orderBy('id', 'desc')
            ->where('students.id', $studentId)
            ->first();
        if (empty($student)) {
            return redirect(route('students.index'));
        }
        if ($student->exam_status == 'Passed') {
            return view('students.certificate.pass.certificate_pass_singal')->with('student', $student)->with('qrCode', $qrCode);
        } else {
            return view('students.certificate.fail.certificate_fail_singal')->with('student', $student)->with('qrCode', $qrCode);
        }
    }

    public function generateCertificate_send(Request $request)
    {

        $studentIds = $request->student_ids_generateCertificate;

        $html = '';
        foreach ($studentIds as $studentId) {
            $student = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation', 'insatitutes.insatitute_name')
                ->leftJoin('districts', 'students.district_id', '=', 'districts.id')
                ->leftJoin('occupations', 'students.occupation_id', '=', 'occupations.id')
                ->leftJoin('insatitutes', 'students.institutionName', '=', 'insatitutes.id')
                ->orderBy('id', 'desc')
                ->where('students.id', $studentId)
                ->first();

            if (empty($student)) {
                continue;
            }

            $qrdata = route('students.qr_details', $studentId);

            $qrCode = QrCode::size(100)->generate($qrdata);

            if ($student->exam_status == 'Passed') {
                $html .= view('students.certificate.pass.certificate_pass_singal')->with('student', $student)->with('qrCode', $qrCode)->render();
            } else {
                $html .= view('students.certificate.fail.certificate_fail_singal')->with('student', $student)->with('qrCode', $qrCode)->render();
            }
        }

        return view('students.generateCertificateBulk')->with('html', $html);
    }


    public function qr_details($studentId)
    {

        $student = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.id', $studentId)
            ->first();
        return view('students.qr_details')->with('student', $student);
    }

    public function forwardToAssessmentCenter_modal(Request $request)
    {

        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('students.id', 'desc')
            ->where('students.assessment_center', null)
            ->whereNotNull('students.registration_number'); // must have registration number first

        if (!can('chairman') && can('district_admin')) {
            $students = $students->where('students.district_id', auth()->user()->district_id);
        }


        if ($request->has('filter_occupation') && $request->filter_occupation != null && $request->filter_occupation != '') {
            $students = $students->where('students.occupation_id', $request->filter_occupation);
        }
        if ($request->has('filter_program') && $request->filter_program != null && $request->filter_program != '') {
            $students = $students->where('students.program_id', $request->filter_program);
        }

        if ($request->has('district_id') && $request->district_id != null && $request->district_id != '') {
            $students = $students->where('students.district_id', $request->district_id);
        }
        if ($request->has('upajila_id') && $request->upajila_id != null && $request->upajila_id != '') {
            $students = $students->where('students.upajila_id', $request->upajila_id);
        }
        if ($request->has('search_term') && $request->search_term != null && $request->search_term != '') {
            $students = $students->where(function ($query) use ($request) {
                $query->where('students.candidate_name', 'like', '%' . $request->search_term . '%')
                    ->orWhere('students.registration_number', 'like', '%' . $request->search_term . '%')
                    ->orWhere('students.candidate_name_bn', 'like', '%' . $request->search_term . '%');
            });
        }








        $students = $students->get();
        $html = '';
        $html .= '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate Id</th>
                    <th>Email</th>
                    <th>Occupation</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $html .= '<tr>
                <td style="font-size: 20px;padding: 3px;text-align: -webkit-center;"><input onclick="forwardToAssessmentCenter_select()" type="checkbox" name="student_ids[]" class="student_ids_forwardToAssessmentCenter" value="' . $student->id . '" style="width: 20px; height: 20px;"></td>
                <td style="font-size: 16px;padding: 3px;text-align: -webkit-center;">' . ++$key . '</td>
                <td style="padding: 3px;" >' . $student->candidate_name . '</td>
                <td style="padding: 3px;" >' . $student->candidate_id . '</td>
                <td style="padding: 3px;" >' . $student->email . '</td>
                <td style="padding: 3px;" >' . $student->occupation . '</td>
                <td style="padding: 3px;" >' . $student->district . '</td>
            </tr>';
        }
        $html .= '</tbody>
        </table>';
        return response()->json($html);
    }

    public function forwardToAssessmentCenter_send(Request $request)
    {
        $student_ids_forwardToAssessmentCenter = $request->student_ids_forwardToAssessmentCenter;
        $assessment_center_id = $request->assessment_center_id;

        $assessment_center = AssessmentCenter::find($assessment_center_id);
        if (empty($assessment_center)) {
            return response()->json([
                'success' => false,
                'message' => "Assessment Center not found",
            ]);
        }
        $assessment_date = $request->assessment_date;
        DB::beginTransaction();
        try {
            foreach ($student_ids_forwardToAssessmentCenter as $studentId) {
                $student = Student::find($studentId);
                $student->assessment_center = $assessment_center_id;
                $student->assessment_date = $assessment_date;
                $student->training_end_date = $assessment_date;
                $student->assessment_center_registration_number = $assessment_center->registration_number;
                $student->status = 'Waiting for the exam results from the Assessment Center';
                $student->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Learner forwarded to Assessment Center successfully",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Operation failed",
            ]);
        }

    }
    public function forwardToDistrictAdmin_modal()
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation', 'programs.program_type')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->join('programs', 'students.program_id', '=', 'programs.id')
            ->orderBy('id', 'desc')
            ->where('students.exam_status', '!=', 'Pending')
            ->where('students.status', '=', 'Waiting for the exam results from the Assessment Center')
            ->get();

        $html = '';
        $html .= '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate Id</th>
                    <th>Exam Status</th>
                    <th>Occupation</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $html .= '<tr>
                <td style="font-size: 20px;padding: 3px;text-align: -webkit-center;"><input onclick="forwardToDistrictAdmin_select()" type="checkbox" name="student_ids[]" class="student_ids_forwardToDistrictAdmin" value="' . $student->id . '" style="width: 20px; height: 20px;"></td>
                <td style="font-size: 16px;padding: 3px;text-align: -webkit-center;">' . ++$key . '</td>
                <td style="padding: 3px;" >' . $student->candidate_name . '</td>
                <td style="padding: 3px;" >' . $student->candidate_id . '</td>
                <td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->program_type == 'Technical' ? ($student->exam_status == 'Fail' ? 'Not Yet Competent' : 'Competent') : ($student->exam_status == 'Fail' ? 'Optainane ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Promising'))) . '</span></td>
                <td style="padding: 3px;" >' . $student->occupation . '</td>
                <td style="padding: 3px;" >' . $student->district . '</td>
            </tr>';
        }
        $html .= '</tbody>
        </table>';
        return response()->json($html);
    }

    public function forwardToDistrictAdmin_send(Request $request)
    {
        $student_ids_forwardToDistrictAdmin = $request->student_ids_forwardToDistrictAdmin;
        DB::beginTransaction();
        try {
            foreach ($student_ids_forwardToDistrictAdmin as $studentId) {
                $student = Student::find($studentId);
                $student->status = 'Waiting for District Admin Approval';
                $student->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Learner forwarded to District Admin successfully",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Operation failed",
            ]);
        }

    }


    public function forwardToChairman_modal()
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.status', '=', 'Waiting for Assessment Controller Approval')
            ->get();
        $html = '';
        $html .= '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate Id</th>
                    <th>Exam Status</th>
                    <th>Occupation</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $html .= '<tr>
                <td style="font-size: 20px;padding: 3px;text-align: -webkit-center;"><input onclick="forwardToChairman_select()" type="checkbox" name="student_ids[]" class="student_ids_forwardToChairman" value="' . $student->id . '" style="width: 20px; height: 20px;"></td>
                <td style="font-size: 16px;padding: 3px;text-align: -webkit-center;">' . ++$key . '</td>
                <td style="padding: 3px;" >' . $student->candidate_name . '</td>
                <td style="padding: 3px;" >' . $student->candidate_id . '</td>
                <td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->exam_status == 'Fail' ? 'Optainane ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Promising')) . '</span></td>
                <td style="padding: 3px;" >' . $student->occupation . '</td>
                <td style="padding: 3px;" >' . $student->district . '</td>
            </tr>';
        }
        $html .= '</tbody>
        </table>';
        return response()->json($html);
    }

    public function forwardToChairman_send(Request $request)
    {
        $student_ids_forwardToChairman = $request->student_ids_forwardToChairman;
        DB::beginTransaction();
        try {
            foreach ($student_ids_forwardToChairman as $studentId) {
                $student = Student::find($studentId);
                $student->status = 'Waiting for Chairman Approval';
                $student->controller_id = auth()->user()->id;
                $student->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Learner forwarded to District Admin successfully",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Operation failed",
            ]);
        }
    }


    public function forwardToAssessmentController_modal()
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.status', '=', 'Waiting for District Admin Approval')
            ->get();
        $html = '';
        $html .= '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate Id</th>
                    <th>Exam Status</th>
                    <th>Occupation</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $html .= '<tr>
                <td style="font-size: 20px;padding: 3px;text-align: -webkit-center;"><input onclick="forwardToAssessmentController_select()" type="checkbox" name="student_ids[]" class="student_ids_forwardToAssessmentController" value="' . $student->id . '" style="width: 20px; height: 20px;"></td>
                <td style="font-size: 16px;padding: 3px;text-align: -webkit-center;">' . ++$key . '</td>
                <td style="padding: 3px;" >' . $student->candidate_name . '</td>
                <td style="padding: 3px;" >' . $student->candidate_id . '</td>
                <td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->exam_status == 'Fail' ? 'Optainane ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Promising')) . '</span></td>
                <td style="padding: 3px;" >' . $student->occupation . '</td>
                <td style="padding: 3px;" >' . $student->district . '</td>
            </tr>';
        }
        $html .= '</tbody>
        </table>';
        return response()->json($html);
    }

    public function forwardToAssessmentController_send(Request $request)
    {
        $student_ids_forwardToChairman = $request->student_ids_forwardToAssessmentController;
        DB::beginTransaction();
        try {
            foreach ($student_ids_forwardToChairman as $studentId) {
                $student = Student::find($studentId);
                $student->status = 'Waiting for Assessment Controller Approval';
                $student->districts_admin_id = auth()->user()->id;
                $student->districts_admin_status = "Approved";
                $student->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Learner forwarded to District Admin successfully",
            ]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Operation failed",
            ]);
        }
    }
    public function backToDistrict_modal()
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.status', '=', 'Waiting for Assessment Controller Approval')
            ->get();
        $html = '';
        $html .= '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate Id</th>
                    <th>Exam Status</th>
                    <th>Occupation</th>
                    <th>District</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $html .= '<tr>
                <td style="font-size: 20px;padding: 3px;text-align: -webkit-center;"><input onclick="backTodistrict_modal_select()" type="checkbox" name="student_ids[]" class="backTodistrict_modal_select" value="' . $student->id . '" style="width: 20px; height: 20px;"></td>
                <td style="font-size: 16px;padding: 3px;text-align: -webkit-center;">' . ++$key . '</td>
                <td style="padding: 3px;" >' . $student->candidate_name . '</td>
                <td style="padding: 3px;" >' . $student->candidate_id . '</td>
                <td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->exam_status == 'Fail' ? 'Optainane ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Promising')) . '</span></td>
                <td style="padding: 3px;" >' . $student->occupation . '</td>
                <td style="padding: 3px;" >' . $student->district . '</td>
                <td style="padding: 3px;" ><input type="text" class="form-control backToDistrict_comments" name="backToDistrict_comments[]"></td></td>
            </tr>';
        }
        $html .= '</tbody>
        </table>';
        return response()->json($html);
    }

    public function backToDistrict_send(Request $request)
    {
        $student_ids_forwardToChairman = $request->student_ids_backToDistrict;
        $comments = $request->comments;
        DB::beginTransaction();
        try {
            foreach ($student_ids_forwardToChairman as $index => $studentId) {
                $student = Student::find($studentId);
                $student->status = 'Waiting for District Admin Approval';
                $student->districts_admin_id = auth()->user()->id;
                $student->districts_admin_status = "Pending";
                $student->controller_back_comments = $comments[$index];
                $student->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Learner Back to District Admin successfully",
            ]);
        } catch (\Exception $e) {
            dd(vars: $e);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Operation failed",
            ]);
        }
    }

    // ─── District Admin: Set Assessment Status (Ready / Dropout / Absent) ───────

    public function setAssessmentStatus_modal(Request $request)
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('students.id', 'desc')
            ->whereIn('students.status', ['Pending', 'Waiting for District Admin Approval'])
            ->where('students.exam_status', 'Pending')   // only show unset students
            ->whereNull('students.registration_number');

        if (!can('chairman') && can('district_admin')) {
            $students = $students->where('students.district_id', auth()->user()->district_id);
        }

        $students = $students->get();



        $html = '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate ID</th>
                    <th>Occupation</th>
                    <th>District</th>
                    <th>Set Status</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $currentStatus = $student->exam_status ?? 'Pending';
            $html .= '<tr>
                <td style="font-size:20px;padding:3px;text-align:-webkit-center;">
                    <input onclick="setAssessmentStatus_select()" type="checkbox" class="student_ids_setAssessmentStatus" value="' . $student->id . '" style="width:20px;height:20px;">
                </td>
                <td style="font-size:16px;padding:3px;text-align:-webkit-center;">' . ++$key . '</td>
                <td style="padding:3px;">' . $student->candidate_name . '</td>
                <td style="padding:3px;">' . $student->candidate_id . '</td>
                <td style="padding:3px;">' . $student->occupation . '</td>
                <td style="padding:3px;">' . $student->district . '</td>
                <td style="padding:3px;">
                    <select class="form-control assessment_status_select" data-id="' . $student->id . '">
                        <option value="Ready for Assessment"' . ($currentStatus == 'Ready for Assessment' ? ' selected' : '') . '>Ready for Assessment</option>
                        <option value="Dropout"' . ($currentStatus == 'Dropout' ? ' selected' : '') . '>Dropout</option>
                    </select>
                </td>
            </tr>';
        }
        $html .= '</tbody></table>';
        return response()->json($html);
    }

    public function setAssessmentStatus_send(Request $request)
    {
        $updates = $request->updates; // array of {id, status}
        DB::beginTransaction();
        try {
            foreach ($updates as $item) {
                Student::where('id', $item['id'])->update(['exam_status' => $item['status']]);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Operation failed']);
        }
    }

    // ─── District Admin: Forward to Assistant Registrar ─────────────────────────

    public function forwardToAssistantRegistrar_modal(Request $request)
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('students.id', 'desc')
            ->whereIn('students.status', ['Pending', 'Waiting for District Admin Approval'])
            ->where('students.exam_status', 'Ready for Assessment')
            ->whereNull('students.registration_number');

        if (!can('chairman') && can('district_admin')) {
            $students = $students->where('students.district_id', auth()->user()->district_id);
        }

        $students = $students->get();


        $html = '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate ID</th>
                    <th>Type</th>
                    <th>Occupation</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $html .= '<tr>
                <td style="font-size:20px;padding:3px;text-align:-webkit-center;">
                    <input onclick="forwardToAssistantRegistrar_select()" type="checkbox" class="student_ids_forwardToAssistantRegistrar" value="' . $student->id . '" style="width:20px;height:20px;">
                </td>
                <td style="font-size:16px;padding:3px;text-align:-webkit-center;">' . ++$key . '</td>
                <td style="padding:3px;">' . $student->candidate_name . '</td>
                <td style="padding:3px;">' . $student->candidate_id . '</td>
                <td style="padding:3px;"><span class="badge badge-info">' . ($student->student_type ?? 'REG') . '</span></td>
                <td style="padding:3px;">' . $student->occupation . '</td>
                <td style="padding:3px;">' . $student->district . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';
        return response()->json($html);
    }

    public function forwardToAssistantRegistrar_send(Request $request)
    {
        $student_ids = $request->student_ids_forwardToAssistantRegistrar;
        DB::beginTransaction();
        try {
            foreach ($student_ids as $studentId) {
                $student = Student::find($studentId);
                $student->status = 'Waiting for Registration';
                $student->save();
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Learner forwarded to Assistant Registrar successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Operation failed']);
        }
    }

    // ─── Assistant Registrar: Give Registration Number ──────────────────────────

    public function giveRegistrationNumber_modal(Request $request)
    {
        $students = Student::select(
            'students.*',
            'districts.name_en as district',
            'districts.code as district_code',
            'occupations.title as occupation',
            'occupations.code as occupation_code',
            'occupations.category as occupation_category'
        )
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.status', 'Waiting for Registration')
            ->get();

        $html = '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Occupation</th>
                    <th>District</th>
                    <th>Preview Registration No.</th>
                </tr>
            </thead>
            <tbody>';

        // Pre-compute serials per group within this modal load
        $groupCounters = [];

        foreach ($students as $key => $student) {
            $type = $student->student_type ?? 'REG';
            $sector = $student->occupation_category ?? 'GEN';
            $occCode = $student->occupation_code ?? '0000';
            $distCode = $student->district_code ?? 'XX';
            $groupKey = "{$type}-{$sector}-{$occCode}-{$distCode}";

            // Count already saved in DB
            if (!isset($groupCounters[$groupKey])) {
                $groupCounters[$groupKey] = Student::where('student_type', $type)
                    ->where('occupation_id', $student->occupation_id)
                    ->where('district_id', $student->district_id)
                    ->whereNotNull('registration_number')
                    ->count();
            }
            $groupCounters[$groupKey]++;
            $serial = str_pad($groupCounters[$groupKey], 4, '0', STR_PAD_LEFT);
            $regNo = "{$type}-{$sector}-{$occCode}-{$distCode}-{$serial}";

            $html .= '<tr>
                <td style="font-size:20px;padding:3px;text-align:-webkit-center;">
                    <input onclick="giveRegistrationNumber_select()" type="checkbox" class="student_ids_giveRegistrationNumber" value="' . $student->id . '" style="width:20px;height:20px;">
                </td>
                <td style="font-size:16px;padding:3px;text-align:-webkit-center;">' . ++$key . '</td>
                <td style="padding:3px;">' . $student->candidate_name . '</td>
                <td style="padding:3px;"><span class="badge badge-info">' . $type . '</span></td>
                <td style="padding:3px;">' . $student->occupation . '</td>
                <td style="padding:3px;">' . $student->district . '</td>
                <td style="padding:3px;"><input type="text" class="form-control reg_no_input" data-student-id="' . $student->id . '" value="' . $regNo . '" style="min-width:210px;font-weight:bold;color:#007bff;" placeholder="Registration No."></td>
            </tr>';
        }
        $html .= '</tbody></table>';
        return response()->json($html);
    }

    public function giveRegistrationNumber_approve(Request $request)
    {
        $student_ids = $request->student_ids_giveRegistrationNumber;
        DB::beginTransaction();
        try {
            foreach ($student_ids as $studentId) {
                $student = Student::select(
                    'students.*',
                    'districts.code as district_code',
                    'occupations.code as occupation_code',
                    'occupations.category as occupation_category'
                )
                    ->join('districts', 'students.district_id', '=', 'districts.id')
                    ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
                    ->where('students.id', $studentId)
                    ->first();

                if (!$student)
                    continue;

                $type = $student->student_type ?? 'REG';
                $sector = $student->occupation_category ?? 'GEN';
                $occCode = $student->occupation_code ?? '0000';
                $distCode = $student->district_code ?? 'XX';

                // Count saved registrations for this group to get the next serial
                $count = Student::where('student_type', $type)
                    ->where('occupation_id', $student->occupation_id)
                    ->where('district_id', $student->district_id)
                    ->whereNotNull('registration_number')
                    ->count();

                // Use custom reg number if provided by user, else auto-generate
                $customNumbers = $request->input('custom_reg_numbers', []);
                if (!empty($customNumbers[$studentId])) {
                    $regNo = $customNumbers[$studentId];
                } else {
                    $serial = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
                    $regNo = "{$type}-{$sector}-{$occCode}-{$distCode}-{$serial}";
                }

                Student::where('id', $studentId)->update([
                    'registration_number' => $regNo,
                    'assistant_registrar_id' => auth()->id(),
                    'assistant_registrar_status' => 'Approved',
                    'status' => 'Waiting for District Admin Approval',
                ]);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registration numbers assigned and returned to District Admin successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('giveRegistrationNumber_approve error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Operation failed: ' . $e->getMessage()]);
        }
    }

    // ─── Registration Card ────────────────────────────────────────────────────────

    public function registrationCard($id)
    {
        $student = Student::select(
            'students.*',
            'occupations.title as occupation',
            'districts.name_en as district_name',
            'insatitutes.insatitute_name as assessment_center_name',
            'insatitutes.center_reg_num as assessment_center_registration_number'
        )
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->leftJoin('insatitutes', 'students.institutionName', '=', 'insatitutes.id')
            ->where('students.id', $id)
            ->firstOrFail();

        return view('students.registration_card', compact('student'));
    }

    // ────────────────────────────────────────────────────────────────────────────

    public function approveStudent_modal()
    {

        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.status', '=', 'Waiting for Chairman Approval')
            ->get();
        $html = '';
        $html .= '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate Id</th>
                    <th>Exam Status</th>
                    <th>Occupation</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $html .= '<tr>
                <td style="font-size: 20px;padding: 3px;text-align: -webkit-center;"><input onclick="approveStudent_select()" type="checkbox" name="student_ids[]" class="student_ids_approveStudent" value="' . $student->id . '" style="width: 20px; height: 20px;"></td>
                <td style="font-size: 16px;padding: 3px;text-align: -webkit-center;">' . ++$key . '</td>
                <td style="padding: 3px;" >' . $student->candidate_name . '</td>
                <td style="padding: 3px;" >' . $student->candidate_id . '</td>
                <td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->exam_status == 'Fail' ? 'Optainane ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Promising')) . '</span></td>
                <td style="padding: 3px;" >' . $student->occupation . '</td>
                <td style="padding: 3px;" >' . $student->district . '</td>
            </tr>';
        }
        $html .= '</tbody>
        </table>';
        return response()->json($html);
    }

    public function approveStudent_send(Request $request)
    {
        $student_ids_approveStudent = $request->student_ids_approveStudent;
        DB::beginTransaction();
        try {
            foreach ($student_ids_approveStudent as $studentId) {
                $student = Student::find($studentId);
                $student->status = 'Chairman Approved';
                $student->chairmen_status = "Approved";
                $student->chairmen_id = auth()->user()->id;
                $student->save();
                $massage = 'প্রিয় ' . $student->candidate_name_bn . ', আপনার প্রশিক্ষণ কোর্সের সার্টিফিকেট প্রস্তুত। অনুগ্রহ করে নির্ধারিত সময়ে আপনার জেলা উপানুষ্ঠানিক শিক্ষা ব্যুরো অফিস থেকে এটি সংগ্রহ করুন।';
                $send = send_sms_new($student->mobile_number, $massage);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Operation successfull",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Operation failed",
            ]);
        }
    }
    public function generateCertificate_modal(Request $request)
    {
        $filter_program = $request->filter_program;
        $filter_occupation = $request->filter_occupation;
        $certificate_type = $request->certificate_type;
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.status', '=', 'Chairman Approved')
            ->where('students.exam_status', '=', $certificate_type)
            ->when($filter_program, function ($query) use ($filter_program) {
                return $query->where('students.program_id', $filter_program);
            })
            ->when($filter_occupation, function ($query) use ($filter_occupation) {
                return $query->where('students.occupation_id', $filter_occupation);
            })
            ->get();
        $html = '';
        $html .= '<table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Candidate Id</th>
                    <th>Exam Status</th>
                    <th>Occupation</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($students as $key => $student) {
            $html .= '<tr>
                <td style="font-size: 20px;padding: 3px;text-align: -webkit-center;"><input onclick="generateCertificate_select()" type="checkbox" name="student_ids[]" class="student_ids_generateCertificate" value="' . $student->id . '" style="width: 20px; height: 20px;"></td>
                <td style="font-size: 16px;padding: 3px;text-align: -webkit-center;">' . ++$key . '</td>
                <td style="padding: 3px;" >' . $student->candidate_name . '</td>
                <td style="padding: 3px;" >' . $student->candidate_id . '</td>
                <td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->exam_status == 'Fail' ? 'Optainane ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Promising')) . '</span></td>
                <td style="padding: 3px;" >' . $student->occupation . '</td>
                <td style="padding: 3px;" >' . $student->district . '</td>
            </tr>';
        }
        $html .= '</tbody>
        </table>';
        return response()->json($html);
    }



    public function get_competences_by_occupation(Request $request)
    {
        $student_id = $request->all()['id'];
        $occupation_id = Student::where('id', $student_id)->first()->occupation_id;
        $competences = Competence::where('occupation_id', $occupation_id)->get();
        $html = '';
        foreach ($competences as $key => $competence) {
            $html .= '<div class="form-check">
                        <input class="form-check-input competence_check" type="checkbox" checked value="' . $competence->id . '" id="competence_' . $competence->id . '" name="competence_ids[]">
                        <label class="form-check-label" for="competence_' . $competence->id . '">
                            ' . $competence->title . '
                        </label>
                    </div>';
        }


        return response()->json($html);
    }


    function convertBanglaDateToEnglish($banglaDate)
    {
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        // Step 1: Replace Bangla digits with English digits
        $englishDate = str_replace($bn, $en, $banglaDate);
        return date('Y-m-d', strtotime($englishDate));
    }
    function bntoen($var)
    {
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $english = str_replace($bn, $en, $var);
        return $english;
    }

    function get_upazila_id($var)
    {
        if ($var == null || $var == '') {
            $var = 'No Data'; // If it's already an ID, return it directly
        }
        $upazila = Upazila::where('name_bn', $var)->first();
        if ($upazila) {
            return $upazila->id;
        } else {

            $new_upazila = new Upazila();
            $new_upazila->name_bn = $var;
            $new_upazila->name_en = $var;
            $new_upazila->dis_id = 12;
            $new_upazila->save();
            return $new_upazila->id;
        }

    }
    function get_occupation_id($var)
    {
        $upazila = Occupation::where('title', $var)->first();
        if ($upazila) {
            return $upazila->id;
        } else {
            $new_upazila = new Occupation();
            $new_upazila->title = $var;
            $new_upazila->description = $var;
            $new_upazila->save();
            return $new_upazila->id;
        }

    }


    function excell_date_convert($number)
    {
        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($number);
        return $date->format('Y-m-d'); // Output: 2024-11-14 
    }

    function get_assessment_center($var)
    {
        if ($var == null || $var == '') {
            return null; // If it's already an ID, return it directly
        }
        $assessment_center = AssessmentCenter::where('center_name', $var)->first();
        if ($assessment_center) {
            return $assessment_center->id;
        } else {
            $new_assessment_center = new AssessmentCenter();
            $new_assessment_center->center_name = $var;
            $new_assessment_center->address = $var;
            $new_assessment_center->registration_number = 'AC' . rand(100000, 999999);
            $new_assessment_center->save();
            return $new_assessment_center->id;
        }

    }


    function get_institution_id($var)
    {
        if ($var == null || $var == '') {
            return null; // If it's already an ID, return it directly
        }
        $institution = Insatitute::where('insatitute_name', $var)->first();
        if ($institution) {
            return $institution->id;
        } else {
            $new_institution = new Insatitute();
            $new_institution->insatitute_name = $var;
            $new_institution->address = $var;
            $new_institution->district = 12;
            $new_institution->status = 'Active';
            $new_institution->description = $var;
            $new_institution->save();
            return $new_institution->id;
        }

    }





    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|array',
            'file.*' => 'mimes:xlsx,xls,csv',
        ]);

        foreach ($request->file('file') as $file) {
            $import = new ExcelDataImport();
            Excel::import($import, $file);

            $data = $import->data;
            foreach ($data as $key => $value) {
                $registration_number = trim($value['registration_number']);
                $candidate_name_en = $value['candidate_name_en'];
                $father_name = $value['father_name'];
                $mother_name = $value['mother_name'];
                $address = $value['address'];
                $assessment_date = $value['assessment_date'];
                $assessment_center = $value['assessment_center'];
                $result = $value['result'];
                $institution_name = $value['institution_name'];
                $compitency = $value['competence'];
                $institute_no = $value['institute_no'];

                if ($result == 'Competent') {
                    $exam_status = 'Passed';
                } elseif ($result == 'Dropout') {
                    $exam_status = 'Dropout';
                } elseif ($result == 'Absent') {
                    $exam_status = 'Absent';
                } else {
                    $exam_status = 'Fail';
                }


                $student = Student::where('registration_number', 'LIKE', '%' . trim($registration_number) . '%')
                    ->where('institution_no_temp', $institute_no)
                    ->where('exam_status', 'LIKE', '%' . 'Pending' . '%')
                    ->first();



                if ($student) {
                    $assessment_center = $this->get_assessment_center($assessment_center);
                    $institution_name = $this->get_institution_id($institution_name);
                    $student->candidate_name = $candidate_name_en;
                    $student->father_name = $father_name;
                    $student->mother_name = $mother_name;
                    $student->address = $address;
                    $student->training_end_date = date('Y-m-d', strtotime(str_replace('.', '-', $assessment_date)));
                    $student->assessment_date = date('Y-m-d', strtotime(str_replace('.', '-', $assessment_date)));
                    $student->assessment_center = $assessment_center;
                    $student->institutionName = $institution_name;
                    $student->exam_status = $exam_status;

                    $student->save();


                    if ($student->exam_status == 'Fail') {
                        $compitency_array = explode('-', $compitency);
                        if (count($compitency_array) == 2) {
                            $compitency_ids = range((int) $compitency_array[0], (int) $compitency_array[1]);
                        } else {
                            $compitency_ids = [(int) $compitency_array[0]];
                        }
                        StudentCompetenceModel::where('student_id', $request->studentId)->delete();
                        $competences = Competence::where('occupation_id', $student->occupation_id)->get();
                        $checkedCompetences = [];
                        foreach ($competences as $key => $competence) {
                            if (in_array($key + 1, $compitency_ids)) {
                                $checkedCompetences[] = $competence->id;
                            }
                        }

                        foreach ($checkedCompetences as $competenceId) {
                            $StudentCompetenceModel = new StudentCompetenceModel();
                            $StudentCompetenceModel->student_id = $student->id;
                            $StudentCompetenceModel->competence_id = $competenceId;
                            $StudentCompetenceModel->save();
                        }
                    }
                }
            }
        }
        echo 'success';
    }
    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv',
    //     ]);
    //     $import = new ExcelDataImport;
    //     Excel::import($import, $request->file('file'));

    //     $data = $import->data;
    //     foreach ($data as $key => $value) {
    //         $registration_number = $value['registration_number'];
    //         $candidate_name_en = $value['candidate_name_en'];
    //         $father_name = $value['father_name'];
    //         $mother_name = $value['mother_name'];
    //         $address = $value['address'];
    //         $assessment_date = $value['assessment_date'];
    //         $assessment_center = $value['assessment_center'];
    //         $result = $value['result'];
    //         $institution_name = $value['institution_name'];
    //         $compitency = $value['compitency'];

    //         if($result == 'Competent'){
    //             $exam_status = 'Passed';
    //         }elseif($result == 'Dropout'){
    //             $exam_status = 'Dropout';
    //         }elseif($result == 'Absent'){
    //             $exam_status = 'Absent';
    //         }else{
    //             $exam_status = 'Fail';
    //         }





    //         $student = Student::where('registration_number', $registration_number)
    //             ->where('institution_no_temp', 1)
    //             ->first();

    //         if ($student) {
    //             $assessment_center = $this->get_assessment_center($assessment_center);
    //             $institution_name = $this->get_institution_id($institution_name);
    //             $student->candidate_name = $candidate_name_en;
    //             $student->father_name = $father_name;
    //             $student->mother_name = $mother_name;
    //             $student->address = $address;
    //             $student->training_end_date = date('Y-m-d', strtotime(str_replace('.', '-', $assessment_date)));
    //             $student->assessment_date = date('Y-m-d', strtotime(str_replace('.', '-', $assessment_date)));
    //             $student->assessment_center = $assessment_center;
    //             $student->institutionName = $institution_name;
    //             $student->exam_status = $exam_status;

    //             $student->save();


    //             if ($student->exam_status == 'Fail') {
    //                 $compitency_array = explode('-', $compitency);
    //                 $compitency_ids = range((int) $compitency_array[0], (int) $compitency_array[1]);
    //                 StudentCompetenceModel::where('student_id', $request->studentId)->delete();
    //                 $competences = Competence::where('occupation_id', $student->occupation_id)->get();
    //                 $checkedCompetences = [];
    //                 foreach ($competences as $key => $competence) {
    //                     if (in_array($key+1, $compitency_ids)) {
    //                         $checkedCompetences[] = $competence->id;
    //                     }
    //                 }

    //                 foreach ($checkedCompetences as $competenceId) {
    //                     $StudentCompetenceModel = new StudentCompetenceModel();
    //                     $StudentCompetenceModel->student_id = $student->id;
    //                     $StudentCompetenceModel->competence_id = $competenceId;
    //                     $StudentCompetenceModel->save();
    //                 }
    //             }
    //         }
    //     }
    //     echo 'success';
    // }
    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv',
    //     ]);
    //     $import = new ExcelDataImport;
    //     Excel::import($import, $request->file('file'));

    //     $data = $import->data;
    //     foreach ($data as $key => $value) {

    //         //dd($value);



    //         $upajila_id = $this->get_upazila_id($value['upajila_id']);
    //         $occupation_id = $this->get_occupation_id($value['occupation_id']);
    //         $date_of_birth = $this->convertBanglaDateToEnglish($value['date_of_birth']);

    //         $training_start_date = $this->convertBanglaDateToEnglish($value['training_start_date']);
    //         $training_end_date = $this->convertBanglaDateToEnglish($value['training_end_date']);

    //         dd($training_end_date);

    //         $data = [
    //             'program_id' => $value['program_id'],
    //             'occupation_id' => $occupation_id,
    //             'registration_number' => $value['registration_number'],
    //             'candidate_id' => $value['candidate_id'],
    //             'candidate_name' => $value['candidate_name_bn'],
    //             'candidate_name_bn' => $value['candidate_name_bn'],
    //             'father_name' => $value['father_name'],
    //             'mother_name' => $value['mother_name'],
    //             'district_id' => 12,
    //             'upajila_id' => $upajila_id,
    //             'address' => $value['address'],
    //             'date_of_birth' => $date_of_birth,
    //             'mobile_number' => $value['mobile_number'],
    //             'admitted_from' => $value['institution_name'],
    //             'age' => $this->bntoen($value['age']),
    //             'literacy_status' => $value['literacy_status'],
    //             'educational_qualification' => $value['educational_qualification'],
    //             'training_start_date' => $training_start_date,
    //             'training_end_date' => $training_end_date,
    //             'gender' => $value['gender'],
    //             'institutionName' => $value['institution_name'],
    //         ];
    //         //dd($data);
    //         $prev_data=Student::where('registration_number',$value['registration_number'])
    //         ->where('program_id',$value['program_id'])
    //         ->where('occupation_id',$occupation_id)
    //         ->where('candidate_id',$value['candidate_id'])
    //         ->where('institutionName',operator: $value['institution_name'])
    //         ->get();
    //         if (count($prev_data)==0) {
    //         Student::create($data);
    //         }
    //     }
    //     echo 'success';
    // }


    public function viewResult(Request $request)
    {
        $student_id = $request->student_id;
        $student = Student::find($student_id);
        $exam_result_sheet = $student->exam_result_sheet;
        $html = asset($exam_result_sheet);
        return response()->json(['html' => $html]);
    }

    // Student Import Methods

    public function import_students_page()
    {
        return view('students.import');
    }

    public function import_students_preview(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $data = Excel::toArray(new ExcelDataImport, $request->file('file'));
        $rows = $data[0] ?? [];


        $students = [];
        $institutes = Insatitute::pluck('insatitute_name', 'id');
        $occupations = Occupation::pluck('title', 'id');
        $districts = District::pluck('name_en', 'id');

        // Clean lookup maps for fuzzy matching
        $institutesMap = [];
        foreach ($institutes as $id => $name)
            $institutesMap[strtolower(trim($name))] = $id;

        $occupationsMap = [];
        foreach ($occupations as $id => $title)
            $occupationsMap[strtolower(trim($title))] = $id;

        $districtsMap = [];
        foreach ($districts as $id => $name)
            $districtsMap[strtolower(trim($name))] = $id;

        // Upazila Map - fetch all as [name => id], might be heavy but needed for fuzzy match
        $upazilas = Upazila::pluck('name_en', 'id');
        $upazilasMap = [];
        foreach ($upazilas as $id => $name)
            $upazilasMap[strtolower(trim($name))] = $id;

        // Assessment Centers Map
        $assessmentCenters = \App\Models\AssessmentCenter::pluck('center_name', 'id');
        $assessmentCentersMap = [];
        foreach ($assessmentCenters as $id => $name) {
            $assessmentCentersMap[strtolower(trim($name))] = $id;
        }

        // Default Upazila if none found
        $defaultUpazilaId = Upazila::first()->id ?? null;


        // Calculate serials preview
        // Note: Real serial calculation happens on save. Here we just show a preview based on CURRENT DB state.
        // We will increment locally to show "0001, 0002" etc. within the preview list if they share the same group
        $groupCounts = []; // key: instId_occId_distId => count

        foreach ($rows as $index => $row) {
            // Map columns - try to guess keys based on common names or just use what we have if specific keys expected
            // Assuming 'name', 'father_name', 'institute', etc. based on sample text in view

            // Helper to get value loosely
            $getVal = function ($keys) use ($row) {
                foreach ($keys as $k) {
                    if (isset($row[$k]))
                        return $row[$k];
                }
                return null;
            };

            $name = $getVal(['learners_name', 'name', 'candidate_name', 'student_name', 'candidate_name_english']);

            if (empty($name) || trim($name) === '') {
                continue;
            }

            $name_bn = $getVal(['name_bn', 'candidate_name_bn', 'candidate_name_bangla']) ?? $name;
            $father = $getVal(['fathars_name', 'father_name', 'father', 'fathers_name_english']);
            $mother = $getVal(['mothers_name', 'mother_name', 'mother', 'mothers_name_english']);
            $nid = $getVal(['nid_if_any', 'nid', 'national_id']);
            $brn = $getVal(['brn', 'birth_registration_number']);
            $mobile = $getVal(['mobile_number', 'mobile', 'phone']);

            // Date Parsing
            if ($index === 0) {
                \Log::info('Import Row 1 Keys: ' . implode(', ', array_keys($row)));
            }

            $dobRaw = $getVal(['date_of_birth_dd_mm_yyyy', 'dob', 'date_of_birth', 'birth_date_dd_mm_yy']);
            $dob = null;
            if ($dobRaw) {
                try {
                    $dob = \Carbon\Carbon::createFromFormat('d.m.Y', $dobRaw)->format('Y-m-d');
                } catch (\Exception $e) {
                    try {
                        $dob = \Carbon\Carbon::parse($dobRaw)->format('Y-m-d');
                    } catch (\Exception $e2) {
                        $dob = null;
                    }
                }
            }

            $gender = $getVal(['gender']);
            $email = $getVal(['email_address', 'email']);
            $address = $getVal(['address']);
            $eduQual = $getVal(['educational_qualification', 'qualification']);

            // Assessment Date
            $assessmentDateRaw = $getVal(['assessment_date', 'date_of_assessment', 'exam_date', 'training_start_date', 'training_start']);
            $assessmentDate = null;
            if ($assessmentDateRaw) {
                try {
                    $assessmentDate = \Carbon\Carbon::createFromFormat('d.m.Y', $assessmentDateRaw)->format('Y-m-d');
                } catch (\Exception $e) {
                    try {
                        $assessmentDate = \Carbon\Carbon::parse($assessmentDateRaw)->format('Y-m-d');
                    } catch (\Exception $e2) {
                        $assessmentDate = null;
                    }
                }
            }

            $programId = $getVal(['program_id']) ?? 1;
            $registrationNo = $getVal(['registration_no', 'registration_number', 'reg_no']);

            $instNameInput = $getVal(['institute', 'institute_name', 'assessment_venue', 'assessment_center']);
            $occNameInput = $getVal(['trade', 'occupation', 'course', 'trade_course_name', 'tradecourse_name']);
            $distNameInput = $getVal(['district', 'district_name']);
            $upazilaInput = $getVal(['upazila', 'upazila_city']);
            $studentType = $getVal(['type', 'student_type']) ?? 'REG';
            $assessmentResult = $getVal(['assessment_result', 'result']);
            $remarks = $getVal(['remarks', 'remark']);

            // Map Exam Status
            $examStatus = null;
            $competencyResult = null; // Internal status for dropdown/logic

            if ($assessmentResult) {
                $rawResult = strtolower(trim($assessmentResult));
                if ($rawResult == 'competent' || $rawResult == 'competency') {
                    $examStatus = 'Passed';
                    $competencyResult = 'Competent';
                } elseif ($rawResult == 'not yet competent' || $rawResult == 'nyc') {
                    $examStatus = 'Fail';
                    $competencyResult = 'Not Yet Competent';
                } elseif ($rawResult == 'absent') {
                    $examStatus = 'Absent';
                    $competencyResult = 'Not Yet Competent';
                } elseif ($rawResult == 'drop_out' || $rawResult == 'drop out') {
                    $examStatus = 'Drop Out';
                    $competencyResult = 'Not Yet Competent';
                } else {
                    $examStatus = $assessmentResult; // Fallback to raw if not matched
                    $competencyResult = $assessmentResult;
                }
            }

            // Resolve IDs
            $instKey = strtolower(trim($instNameInput));
            $instId = $institutesMap[$instKey] ?? null;

            // Fuzzy Institute Match if not found
            if (!$instId && $instNameInput) {
                $bestMatchInst = null;
                $shortestDistInst = -1;
                foreach ($institutesMap as $mapKey => $mapId) {
                    $lev = levenshtein($instKey, $mapKey);
                    if ($lev == 0) {
                        $bestMatchInst = $mapId;
                        break;
                    }
                    if ($lev <= 3 || ($lev <= 6 && strlen($instKey) > 10)) { // Allow some tolerance
                        if ($shortestDistInst == -1 || $lev < $shortestDistInst) {
                            $shortestDistInst = $lev;
                            $bestMatchInst = $mapId;
                        }
                    }
                }
                if ($bestMatchInst) {
                    $instId = $bestMatchInst;
                }
            }

            $instTypeInput = $getVal(['institute_type', 'venue_type', 'center_type', 'type_of_institute']);

            $assessmentCenterInput = $getVal(['assessment_center', 'center_name']);

            // Determine the name to use for Assessment Center (Specific input or fallback to Institute Name)
            $targetAcName = $assessmentCenterInput ?: $instNameInput;
            $acId = null;

            if ($targetAcName) {
                $acKey = strtolower(trim($targetAcName));
                $acId = $assessmentCentersMap[$acKey] ?? null;

                if (!$acId) {
                    try {
                        $newAc = \App\Models\AssessmentCenter::create([
                            'center_name' => $targetAcName,
                            'district_id' => 1, // Default
                            'registration_number' => '',
                            'address' => 'Imported via Excel'
                        ]);
                        $acId = $newAc->id;
                        $assessmentCentersMap[$acKey] = $acId;
                    } catch (\Exception $e) {
                        \Log::error("Failed to create Assessment Center: " . $e->getMessage());
                    }
                }
            }

            // Dynamic Institute Creation
            if (!$instId && $instNameInput) {
                // Determine Code
                $maxCode = Insatitute::max('code');
                $nextCode = str_pad(($maxCode ? $maxCode + 1 : 1), 4, '0', STR_PAD_LEFT);

                try {
                    $newInst = Insatitute::create([
                        'insatitute_name' => $instNameInput,
                        'type' => $instTypeInput ?? 'IBC', // Use valid type or default
                        'code' => $nextCode,
                        'district' => 1, // Default dummy district
                        'address' => 'Imported',
                        'description' => 'Imported via Excel',
                        'status' => 'Active'
                    ]);

                    $instId = $newInst->id;
                    $institutesMap[$instKey] = $instId; // Update key map
                } catch (\Exception $e) {
                    \Log::error("Failed to create institute: " . $e->getMessage());
                }
            } else {
                if ($instNameInput && !$instId) {
                    \Log::info("Institute not found and creation skipped or failed logic: '$instNameInput'");
                }
            }

            // Exact match
            $occKey = strtolower(trim($occNameInput));
            $occId = $occupationsMap[$occKey] ?? null;

            // Fuzzy Occupation Match
            if (!$occId && $occNameInput) {
                $bestMatchOcc = null;
                $shortestDistOcc = -1;
                $inputLen = strlen($occKey);
                // Dynamic threshold: strict for short strings
                $threshold = ($inputLen < 5) ? 1 : (($inputLen < 9) ? 2 : 3);

                foreach ($occupationsMap as $mapKey => $mapId) {
                    $lev = levenshtein($occKey, $mapKey);
                    if ($lev <= $threshold) {
                        if ($shortestDistOcc == -1 || $lev < $shortestDistOcc) {
                            $shortestDistOcc = $lev;
                            $bestMatchOcc = $mapId;
                        }
                    }
                }
                if ($bestMatchOcc) {
                    $occId = $bestMatchOcc;
                }
            }

            // dd($occId,$occNameInput);
            // Dynamic Occupation Creation
            if (!$occId && $occNameInput) {
                try {
                    $maxOccCode = Occupation::max('code');
                    // Ensure maxCode is treated as integer for incrementing if it's numeric, otherwise fallback
                    $nextOccCode = (is_numeric($maxOccCode) ? $maxOccCode + 1 : rand(1000, 9999));
                    $nextOccCode = str_pad($nextOccCode, 4, '0', STR_PAD_LEFT);

                    $newOcc = Occupation::create([
                        'title' => $occNameInput,
                        'code' => $nextOccCode,
                        'description' => 'Imported via Excel'
                    ]);

                    $occId = $newOcc->id;
                    $occupationsMap[$occKey] = $occId; // Update map to avoid duplicates in loop
                } catch (\Exception $e) {
                    \Log::error("Failed to create occupation: " . $e->getMessage());
                }
            }

            $distId = $districtsMap[strtolower(trim($distNameInput))] ?? null;
            $upazilaId = $upazilasMap[strtolower(trim($upazilaInput))] ?? ($defaultUpazilaId ?? null);

            // If upazila input is just an ID (numeric), use it directly if map failed? No, let's stick to name match or provide ID in excel. Sample has name.

            // Generate Preview ID
            $importedCandidateId = $getVal(['candidate_id', 'id']); // Check if provided

            $previewId = $importedCandidateId ?? 'Wait for Save';

            if (!$importedCandidateId && $instId && $occId && $distId) {
                $groupKey = "{$instId}_{$occId}_{$distId}";
                if (!isset($groupCounts[$groupKey])) {
                    $query = Student::where('district_id', $distId)
                        ->where('occupation_id', $occId)
                        ->where('institutionName', $instId);

                    if ($programId) {
                        $query->where('program_id', $programId); // Use imported program ID
                    }

                    $groupCounts[$groupKey] = $query->count();
                }
                $groupCounts[$groupKey]++;
                $currentSerial = $groupCounts[$groupKey];

                $instObj = Insatitute::find($instId);
                $occObj = Occupation::find($occId);
                $distObj = District::find($distId);

                if ($instObj && $occObj && $distObj) {
                    $t = $instObj->type ?? 'XXX';
                    $tc = $occObj->code ?? 'XXX';
                    $dc = $distObj->code ?? 'XX';
                    $ic = $instObj->code ?? 'XXXX';
                    $s = str_pad($currentSerial, 4, '0', STR_PAD_LEFT);

                    $previewId = "{$t}-{$tc}-{$dc}-{$ic}-{$s}";
                }
            }

            $students[] = [
                'candidate_name' => $name,
                'candidate_name_bn' => $name_bn,
                'father_name' => $father,
                'mother_name' => $mother,
                'nid' => $nid,
                'brn' => $brn,
                'registration_number' => $registrationNo,
                'mobile_number' => $mobile,
                'date_of_birth' => $dob,
                'gender' => $gender,
                'email' => $email,
                'address' => $address,
                'educational_qualification' => $eduQual,
                'assessment_date' => $assessmentDate,
                'institutionName' => $instId,
                'assessment_center' => $acId, // Use Resolved ID
                'assessment_venue' => $targetAcName,   // Use Name for Venue text if needed, or keep empty if user prefers. Let's use name.
                'occupation_id' => $occId,
                'district_id' => $distId,
                'upajila_id' => $upazilaId,
                'program_id' => $programId,
                'student_type' => $studentType,
                'competency_status' => $competencyResult, // Use mapped status for dropdown/logic
                'exam_status' => $examStatus,             // Save to exam_status column
                'competency_remarks' => $remarks,
                'competency_remarks' => $remarks,
                'preview_id' => $previewId,
                'institute_type' => $instTypeInput // Pass extracted type
            ];
        }

        // Re-fetch institutes to include any newly created ones
        $institutes = Insatitute::pluck('insatitute_name', 'id');

        return view('students.import_preview', compact('students', 'institutes', 'occupations', 'districts', 'upazilas'));
    }

    public function import_students_store(Request $request)
    {
        // Clear any previous output
        if (ob_get_contents()) {
            ob_end_clean();
        }

        try {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 300);

            // Set headers to ensure JSON response
            header('Content-Type: application/json');

            $importedData = $request->input('students');

            // DEBUG: Log the incoming data
            \Log::info('IMPORT CHUNK: Records received: ' . (is_array($importedData) ? count($importedData) : 'not an array'));

            if (!$importedData || !is_array($importedData)) {
                \Log::error('Import chunk failed: No valid data received');
                return response()->json(['success' => false, 'message' => 'No data to save.'], 400);
            }

            $count = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($importedData as $index => $data) {
                try {
                    // Validate required fields exist
                    if (empty($data['candidate_name']) || !isset($data['occupation_id'])) {
                        $errorCount++;
                        $errors[] = "Row " . ($index + 1) . ": Missing required fields (name or occupation)";
                        \Log::warning('Row ' . ($index + 1) . ' skipped: Missing required fields');
                        \Log::warning($data);
                        continue;
                    }

                    $instituteId = $data['institutionName'] ?? null;
                    $occupationId = $data['occupation_id'] ?? null;
                    $districtId = $data['district_id'] ?? null;

                    // Generate Real Candidate ID or Use Provided
                    $candidateId = isset($data['candidate_id']) && $data['candidate_id'] !== 'Wait for Save' ? $data['candidate_id'] : null;

                    if (!$candidateId && $instituteId && $occupationId && $districtId) {
                        // Generation Logic
                        $institute = Insatitute::find($instituteId);
                        $occupation = Occupation::find($occupationId);
                        $district = District::find($districtId);

                        if ($institute) {
                            // Update Institute Type if provided and different
                            if (isset($data['institute_type']) && !empty($data['institute_type'])) {
                                if ($institute->type != $data['institute_type']) {
                                    $institute->type = $data['institute_type'];
                                    $institute->save();
                                }
                            }
                        }

                        if ($institute && $occupation && $district) {
                            $type = $institute->type ?? 'XXX';
                            $tradeCode = $occupation->code ?? 'XXX';
                            $distCode = $district->code ?? 'XX';
                            $instCode = $institute->code ?? 'XXXX';

                            $query = Student::where('district_id', $districtId)
                                ->where('occupation_id', $occupationId)
                                ->where('institutionName', $instituteId);

                            if (isset($data['program_id'])) {
                                $query->where('program_id', $data['program_id']);
                            }

                            $existingCount = $query->count();
                            $serial = str_pad($existingCount + 1, 4, '0', STR_PAD_LEFT);
                            $candidateId = "{$type}-{$tradeCode}-{$distCode}-{$instCode}-{$serial}";
                        }
                    }

                    $data['candidate_id'] = $candidateId;

                    // Set Default Statuses
                    $data['chairmen_status'] = 'Approved';
                    $data['districts_admin_status'] = 'Approved';
                    $data['status'] = 'Chairman Approved';

                    // Set Static IDs
                    $data['chairmen_id'] = 1;
                    $data['controller_id'] = 1;

                    // Set District Admin ID (Find a user for this district)
                    $districtUser = \App\Models\User::where('district_id', $districtId)->first();
                    $data['districts_admin_id'] = $districtUser ? $districtUser->id : null;

                    // Extract and remove fields that shouldn't be in Student table
                    $compStatus = $data['competency_status'] ?? null;
                    $compRemarks = $data['competency_remarks'] ?? null;

                    // Remove non-fillable fields
                    unset($data['preview_id']);
                    unset($data['competency_status']);
                    unset($data['competency_remarks']);
                    unset($data['assessment_venue']);
                    unset($data['institute_type']);

                    // Ensure program_id is set
                    $data['program_id'] = $data['program_id'] ?? 1;

                    // Validate and clean data
                    $dataToCreate = [];
                    foreach ($data as $key => $value) {
                        // Skip empty or null values for certain fields
                        if ($key === 'registration_number' && empty($value)) {
                            continue;
                        }
                        $dataToCreate[$key] = $value;
                    }

                    // Create student record
                    $student = Student::create($dataToCreate);
                    $count++;

                    // Handle Competency Logic
                    if ($occupationId && $compStatus) {
                        $competencies = Competence::where('occupation_id', $occupationId)->orderBy('id')->get();
                        $status = strtolower(trim($compStatus));

                        if ($status === 'competent') {
                            // Mark ALL competencies as passed
                            foreach ($competencies as $comp) {
                                StudentCompetenceModel::create([
                                    'student_id' => $student->id,
                                    'competence_id' => $comp->id
                                ]);
                            }
                        } elseif ($status === 'not yet competent' || $status === 'nyc') {
                            // Mark specific competencies as failed
                            $failedIndices = [];
                            if ($compRemarks) {
                                $parts = explode(',', $compRemarks);
                                foreach ($parts as $p) {
                                    $val = (int) trim($p);
                                    if ($val > 0) {
                                        $failedIndices[] = $val;
                                    }
                                }
                            }

                            $compIndex = 1;
                            foreach ($competencies as $comp) {
                                if (!in_array($compIndex, $failedIndices)) {
                                    StudentCompetenceModel::create([
                                        'student_id' => $student->id,
                                        'competence_id' => $comp->id
                                    ]);
                                }
                                $compIndex++;
                            }
                        }
                    }

                } catch (\Exception $rowError) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 1) . ": " . $rowError->getMessage();
                    \Log::error('Row ' . ($index + 1) . ' failed: ' . $rowError->getMessage() . ' | Stack: ' . $rowError->getTraceAsString());
                }
            }

            \Log::info('IMPORT CHUNK COMPLETE', [
                'chunk_records_received' => count($importedData),
                'successfully_saved' => $count,
                'failed' => $errorCount
            ]);

            return response()->json([
                'success' => true,
                'message' => "$count students imported in this chunk",
                'saved' => $count,
                'failed' => $errorCount,
                'errors' => $errors
            ], 200);

        } catch (\Throwable $e) {
            // Clear any buffered output to ensure clean JSON response
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            \Log::error('Import chunk processing failed: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine() . ' | Stack: ' . $e->getTraceAsString());

            // Ensure we return JSON even on fatal error
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function download_import_sample()
    {
        return Excel::download(new \App\Exports\SampleStudentExport, 'sample_students.xlsx');
    }
}
