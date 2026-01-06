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
                    ->orWhere('students.candidate_name_bn', 'like', '%' . $request->search_term . '%');
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
    public function get_candidate_number_preview(Request $request) {
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
            $count = Student::where('district_id', $districtId)
                            ->where('occupation_id', $occupationId)
                            ->where('institutionName', $instituteId)
                            ->count();
            
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

                $count = Student::where('district_id', $districtId)
                                ->where('occupation_id', $occupationId)
                                ->where('institutionName', $instituteId)
                                ->count();
                
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
                if (request()->is('general_students*')) {
                    return redirect(route('students.index'));
                } else {
                    return redirect(route('general_students.index'));
                }
            } else {
                return redirect(route('general_students.index'));
            }
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
                if (request()->is('general_students*')) {
                    return redirect(route('students.index'));
                } else {
                    return redirect(route('general_students.index'));
                }
            } else {
                return redirect(route('general_students.index'));
            }
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
            if (request()->is('general_students*')) {
                return redirect(route('students.index'));
            } else {
                return redirect(route('general_students.index'));
            }
        } else {
            return redirect(route('general_students.index'));
        }
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
                return redirect(route('students.index'));
            } else {
                return redirect(route('general_students.index'));
            }
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
            ->orderBy('id', 'desc')
            ->where('students.assessment_center', null);
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
        $certificate_type = $request->certificate_type;
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.status', '=', 'Chairman Approved')
            ->where('students.exam_status', '=', $certificate_type)
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

                if($result == 'Competent'){
                    $exam_status = 'Passed';
                }elseif($result == 'Dropout'){
                    $exam_status = 'Dropout';
                }elseif($result == 'Absent'){
                    $exam_status = 'Absent';
                }else{
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
                        if(count($compitency_array) == 2){
                        $compitency_ids = range((int) $compitency_array[0], (int) $compitency_array[1]);
                        }else{
                            $compitency_ids = [(int) $compitency_array[0]];
                        }
                        StudentCompetenceModel::where('student_id', $request->studentId)->delete();
                        $competences = Competence::where('occupation_id', $student->occupation_id)->get();
                        $checkedCompetences = [];
                        foreach ($competences as $key => $competence) {
                            if (in_array($key+1, $compitency_ids)) {
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
        foreach ($institutes as $id => $name) $institutesMap[strtolower(trim($name))] = $id;
        
        $occupationsMap = [];
        foreach ($occupations as $id => $title) $occupationsMap[strtolower(trim($title))] = $id;

        $districtsMap = [];
        foreach ($districts as $id => $name) $districtsMap[strtolower(trim($name))] = $id;
        
        // Upazila Map - fetch all as [name => id], might be heavy but needed for fuzzy match
        $upazilas = Upazila::pluck('name', 'id');
        $upazilasMap = [];
        foreach ($upazilas as $id => $name) $upazilasMap[strtolower(trim($name))] = $id;


        // Calculate serials preview
        // Note: Real serial calculation happens on save. Here we just show a preview based on CURRENT DB state.
        // We will increment locally to show "0001, 0002" etc. within the preview list if they share the same group
        $groupCounts = []; // key: instId_occId_distId => count

        foreach ($rows as $index => $row) {
            // Map columns - try to guess keys based on common names or just use what we have if specific keys expected
            // Assuming 'name', 'father_name', 'institute', etc. based on sample text in view
            
            // Helper to get value loosely
            $getVal = function($keys) use ($row) {
                foreach ($keys as $k) {
                    if (isset($row[$k])) return $row[$k];
                }
                return null;
            };

            $name = $getVal(['name', 'candidate_name', 'student_name', 'candidate_name_english']);
            $name_bn = $getVal(['name_bn', 'candidate_name_bn', 'candidate_name_bangla']);
            $father = $getVal(['father_name', 'father', 'fathers_name_english']);
            $mother = $getVal(['mother_name', 'mother', 'mothers_name_english']);
            $nid = $getVal(['nid', 'national_id']);
            $brn = $getVal(['brn', 'birth_registration_number']);
            $mobile = $getVal(['mobile', 'mobile_number', 'phone']);
            $dob = $getVal(['dob', 'date_of_birth']);
            $gender = $getVal(['gender']);
            $email = $getVal(['email']);
            $address = $getVal(['address']);
            $eduQual = $getVal(['qualification', 'educational_qualification']);
            $trainingStart = $getVal(['training_start_date', 'training_start']);
            $programId = $getVal(['program_id', 'program']);

            $instNameInput = $getVal(['institute', 'institute_name']);
            $occNameInput = $getVal(['trade', 'occupation', 'course', 'trade_course_name']);
            $distNameInput = $getVal(['district', 'district_name']);
            $upazilaInput = $getVal(['upazila', 'upazila_city']);
            $studentType = $getVal(['type', 'student_type']) ?? 'REG';

            // Resolve IDs
            $instId = $institutesMap[strtolower(trim($instNameInput))] ?? null;
            $occId = $occupationsMap[strtolower(trim($occNameInput))] ?? null;
            $distId = $districtsMap[strtolower(trim($distNameInput))] ?? null;
            $upazilaId = $upazilasMap[strtolower(trim($upazilaInput))] ?? null; // Try map
            
            // If upazila input is just an ID (numeric), use it directly if map failed? No, let's stick to name match or provide ID in excel. Sample has name.

            // Generate Preview ID
            $previewId = 'Wait for Save';
            if ($instId && $occId && $distId) {
                $groupKey = "{$instId}_{$occId}_{$distId}";
                if (!isset($groupCounts[$groupKey])) {
                    $groupCounts[$groupKey] = Student::where('district_id', $distId)
                        ->where('occupation_id', $occId)
                        ->where('institutionName', $instId)
                        ->count();
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
                'mobile_number' => $mobile,
                'date_of_birth' => $dob,
                'gender' => $gender,
                'email' => $email,
                'address' => $address,
                'educational_qualification' => $eduQual,
                'training_start_date' => $trainingStart,
                'institutionName' => $instId,
                'occupation_id' => $occId,
                'district_id' => $distId,
                'upajila_id' => $upazilaId,
                'program_id' => $programId,
                'student_type' => $studentType,
                'preview_id' => $previewId
            ];
        }

        return view('students.import_preview', compact('students', 'institutes', 'occupations', 'districts', 'upazilas'));
    }

    public function import_students_store(Request $request)
    {
        $importedData = $request->input('students');
        
        if (!$importedData || !is_array($importedData)) {
            Flash::error('No data to save.');
            return redirect(route('students.import_page'));
        }

        $count = 0;
        foreach ($importedData as $data) {
            // Generate Real Candidate ID
            $instituteId = $data['institutionName'] ?? null;
            $occupationId = $data['occupation_id'] ?? null;
            $districtId = $data['district_id'] ?? null;

            if ($instituteId && $occupationId && $districtId) {
                $institute = Insatitute::find($instituteId);
                $occupation = Occupation::find($occupationId);
                $district = District::find($districtId);
                
                if ($institute && $occupation && $district) {
                   $type = $institute->type ?? 'XXX';
                   $tradeCode = $occupation->code ?? 'XXX';
                   $distCode = $district->code ?? 'XX';
                   $instCode = $institute->code ?? 'XXXX';
   
                   $existingCount = Student::where('district_id', $districtId)
                                   ->where('occupation_id', $occupationId)
                                   ->where('institutionName', $instituteId)
                                   ->count();
                   
                   $serial = str_pad($existingCount + 1, 4, '0', STR_PAD_LEFT);
                   $data['candidate_id'] = "{$type}-{$tradeCode}-{$distCode}-{$instCode}-{$serial}";
                }
           }

           // Cleanup
           if (isset($data['preview_id'])) unset($data['preview_id']);

           Student::create($data);
           $count++;
        }

        Flash::success($count . ' students imported successfully.');
        return redirect(route('students.index'));
    }

    public function download_import_sample()
    {
        return Excel::download(new \App\Exports\SampleStudentExport, 'sample_students.xlsx');
    }
}
