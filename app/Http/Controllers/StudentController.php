<?php

namespace App\Http\Controllers;
use App\Models\StudentCompetenceModel;
use App\Models\AssessmentCenter;
use App\Models\Competence;
use App\Models\Student;
use App\Models\Upazila;
use App\Models\Occupation;


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
    public function get_table(Request $request)
    {
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
            }
        }
        if ($request->has('occupation_id') && $request->occupation_id != null) {
            $students = $students->where('students.occupation_id', $request->occupation_id);
        }


        if ($request->has('program_type') && $request->program_type == 'General') {
            $students = $students->where('programs.program_type', 'General');
        } else {
            $students = $students->where('programs.program_type', 'Technical');
        }







        $students = $students->get();


        $html = '';
        foreach ($students as $key => $student) {
            $html .= '<tr>';
            $html .= '<td>' . ($key + 1) . '</td>';
            $html .= '<td>';
            $html .= '<div style="line-height: 1px;">';
            $html .= '<p style="font-weight: bold;color: #000"> ' . $student->candidate_name . '</p>';
            $html .= '<div style="line-height: 2px;">';
            $html .= '<p style="font-size: 10px;"><strong>Occupation:</strong> ' . $student->occupation . '</p>';
            $html .= '<p style="font-size: 10px;"><strong>Regis. No:</strong> ' . $student->registration_number . '</p>';
            $html .= '<p style="font-size: 10px;"><strong>District:</strong> ' . $student->district . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '<td width="10%"><span class="badge badge-' . ($student->status == 'Pending' ? 'warning' : 'success') . '">' . $student->status . '</span></td>';

            if ($request->has('program_type') && $request->program_type == 'General') {
                $html .= '<td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->exam_status == 'Fail' ? 'Optainane ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Promising')) . '</span></td>';
            } else {
                $html .= '<td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->exam_status == 'Fail' ? 'Not Competent yet ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Competent')) . '</span></td>';
            }

            $html .= '<td><span class="badge badge-' . ($student->districts_admin_status == 'Pending' ? 'warning' : 'success') . '">' . $student->districts_admin_status . '</span></td>';
            $html .= '<td><span class="badge badge-' . ($student->chairmen_status == 'Pending' ? 'warning' : 'success') . '">' . $student->chairmen_status . '</span></td>';
            $html .= '<td>';
            $html .= '<div class="btn-group">';
            $html .= '<button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $html .= '<i class="im im-icon-List2" data-placement="top" title="Actions">Action</i>';
            $html .= '</button>';
            $html .= '<div class="dropdown-menu">';
            if (request()->is('general_students*')) {
                $html .= '<a class="dropdown-item" href="' . route('students.show', [$student->id]) . '"><i class="im im-icon-Eye"></i> View</a>';
            } else {
                $html .= '<a class="dropdown-item" href="' . route('general_students.show', [$student->id]) . '"><i class="im im-icon-Eye"></i> View</a>';
            }
            if ($student->status != 'Chairman Approved') {
                if (request()->is('general_students*')) {
                    $html .= '<a class="dropdown-item" href="' . route('students.edit', [$student->id]) . '"><i class="im im-icon-Pen"></i> Edit</a>';
                } else {
                    $html .= '<a class="dropdown-item" href="' . route('general_students.edit', [$student->id]) . '"><i class="im im-icon-Pen"></i> Edit</a>';
                }
            }
            if (can('give_exam_result') && $student->status == 'Waiting for the exam results from the Assessment Center') {
                $html .= '<a class="dropdown-item" onclick="give_exam_result(' . $student->id . ')" href="javascript:void(0);"><i class="im im-icon-Pencil-Ruler"></i> Give Exam Result</a>';
            }
            if (can('district_admin') && $student->status == 'Waiting for District Admin Approval') {
                // $html .= '<a class="dropdown-item" href="' . route('students.forward_to_chairman', [$student->id]) . '"><i class="im im-icon-Arrow-Back"></i> Approve And Send To Chairman</a>';
            }
            if (can('chairman') && $student->status == 'Waiting for Chairman Approval') {
                $html .= '<a class="dropdown-item" href="' . route('students.chairman_approve', [$student->id]) . '"><i class="im im-icon-Approved-Window"></i> Approve</a>';
            }
            if ($student->exam_status == 'Pending') {
                if (request()->is('general_students*')) {

                    $html .= Form::open(['route' => ['students.destroy', $student->id], 'method' => 'delete']);
                } else {
                    $html .= Form::open(['route' => ['general_students.destroy', $student->id], 'method' => 'delete']);
                }

                $html .= Form::button('<i class="im im-icon-Remove"></i> Delete', [
                    'type' => 'submit',
                    'class' => 'dropdown-item',
                    'onclick' => "return confirm('Are you sure?')",
                ]);
                $html .= Form::close();

            }
            if ($student->status == 'Chairman Approved' && $student->exam_status != 'Fail') {
                $html .= '<a class="dropdown-item" target="_blank" href="' . route('students.generate_certificate', [$student->id]) . '"><i class="im im-icon-People-onCloud"></i> Generate Certificate</a>';
            }

            // if ($student->status == 'Chairman Approved' && $student->exam_status != 'Fail') {
            //     $html .= '<a class="dropdown-item" target="_blank" href="' . route('students.generate_certificate', [$student->id]) . '"><i class="im im-icon-People-onCloud"></i> Generate Certificate</a>';
            // }


            $html .= '</div>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        return response()->json([
            'success' => true,
            'html' => $html
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
    public function store(CreateStudentRequest $request)
    {
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

        $student_type = $input['student_type'];
        unset($input['student_type']);

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



        if (empty($student)) {
            Flash::error('Student not found');

            if ($student_type == 'general') {
                return redirect(route('general_students.index'));
            } else {
                return redirect(route('students.index'));
            }
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
            return redirect(route('students.index'));
        } else {
            return redirect(route('general_students.index'));
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
        $student = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.id', $studentId)
            ->first();
        return view('students.generate_certificate')->with('student', $student)->with('qrCode', $qrCode);
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

    public function forwardToAssessmentCenter_modal()
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.assessment_center', null);
        if (!can('chairman') && can('district_admin')) {
            $students = $students->where('students.district_id', auth()->user()->district_id);
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
                $student->assessment_center_registration_number = $assessment_center->registration_number;
                $student->status = 'Waiting for the exam results from the Assessment Center';
                $student->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Lerner forwarded to Assessment Center successfully",
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
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
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
                <td><span class="badge badge-' . ($student->exam_status == 'Fail' ? 'danger' : ($student->exam_status == 'Pending' ? 'warning' : 'success')) . '">' . ($student->exam_status == 'Fail' ? 'Optainane ' : ($student->exam_status == 'Pending' ? 'Pending' : 'Promising')) . '</span></td>
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
                'message' => "Lerner forwarded to District Admin successfully",
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
                $student->districts_admin_id = auth()->user()->id;
                $student->districts_admin_status = "Approved";
                $student->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Lerner forwarded to District Admin successfully",
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
                'message' => "Lerner forwarded to District Admin successfully",
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
            </tr>';
        }
        $html .= '</tbody>
        </table>';
        return response()->json($html);
    }

    public function backToDistrict_send(Request $request)
    {
        $student_ids_forwardToChairman = $request->student_ids_backToDistrict;
        DB::beginTransaction();
        try {
            foreach ($student_ids_forwardToChairman as $studentId) {
                $student = Student::find($studentId);
                $student->status = 'Waiting for District Admin Approval';
                $student->districts_admin_id = auth()->user()->id;
                $student->districts_admin_status = "Pending";
                $student->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Lerner Back to District Admin successfully",
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


    public function generateCertificate_modal()
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.status', '=', 'Chairman Approved')
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

    public function generateCertificate_send(Request $request)
    {
        $student_ids_approveStudent = $request->all()['student_ids_generateCertificate'];
        $data = [];
        foreach ($student_ids_approveStudent as $studentId) {
            $ro = route('students.qr_details', $studentId);
            $qrCode = QrCode::size(100)->generate($ro);
            $student = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
                ->join('districts', 'students.district_id', '=', 'districts.id')
                ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
                ->orderBy('id', 'desc')
                ->where('students.id', $studentId)
                ->first();
            $data[] = [
                'student' => $student,
                'qrCode' => $qrCode,
            ];
        }
        return view('students.generate_certificate_bulk')->with('data', $data);
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


    function excell_date_convert($number){
        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($number);
        return $date->format('Y-m-d'); // Output: 2024-11-14 
    }





    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);


        $import = new ExcelDataImport;
        Excel::import($import, $request->file('file'));

        $data = $import->data;
        foreach ($data as $key => $value) {

            $upajila_id = $this->get_upazila_id($value['upajila_id']);
            $occupation_id = $this->get_occupation_id($value['occupation_id']);
            $date_of_birth = $this->convertBanglaDateToEnglish($value['date_of_birth']);

            $training_start_date=$this->excell_date_convert($value['training_start_date']);
            $training_end_date=$this->excell_date_convert($value['training_end_date']);

            $data = [
                'program_id' => $value['program_id'],
                'occupation_id' => $occupation_id,
                'registration_number' => $value['registration_number'],
                'candidate_id' => $value['candidate_id'],
                'candidate_name' => $value['candidate_name_bn'],
                'candidate_name_bn' => $value['candidate_name_bn'],
                'father_name' => $value['father_name'],
                'mother_name' => $value['mother_name'],
                'district_id' => 12,
                'upajila_id' => $upajila_id,
                'address' => $value['address'],
                'date_of_birth' => $date_of_birth,
                'mobile_number' => $value['mobile_number'],
                'admitted_from' => 'From this institution',
                'age' => $this->bntoen($value['age']),
                'literacy_status' => $value['literacy_status'],
                'educational_qualification' => $value['educational_qualification'],
                'training_start_date' => $training_start_date,
                'training_end_date' => $training_end_date,
                'gender' => $value['gender'],
            ];
            Student::create($data);
        }
        echo 'success';
    }

}
