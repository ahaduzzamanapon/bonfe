<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Student;
use Illuminate\Http\Request;
use Flash;
use Response;

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
        /** @var Student $students */
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc');
        if(!can('chairman') && can('district_admin')) {
            $students = $students->where('students.district_id', auth()->user()->district_id);
        }
        $students = $students-> paginate(10);
        return view('students.index')
        ->with('students', $students);
    }
    public function students_waiting_for_district_approval(Request $request)
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc');
        if(!can('chairman') && can('district_admin')) {
            $students = $students->where('students.district_id', auth()->user()->district_id);
        }
        $students = $students->where('students.status', 'Waiting for District Admin Approval');
        $students = $students-> paginate(10);
        return view('students.index')
            ->with('students', $students);

    }
    public function students_waiting_for_chairman_approval(Request $request)
    {
        $students = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc');
        if(!can('chairman') && can('district_admin')) {
            $students = $students->where('students.district_id', auth()->user()->district_id);
        }
        $students = $students->where('students.status', 'Waiting for Chairman Approval');
        $students = $students-> paginate(10);
        return view('students.index')
            ->with('students', $students);
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

        /** @var Student $student */
        $student = Student::create($input);

        Flash::success('Student saved successfully.');

        return redirect(route('students.index'));
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
        $student = Student::find($id);

        if (empty($student)) {
            Flash::error('Student not found');

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

        if (empty($student)) {
            Flash::error('Student not found');

            return redirect(route('students.index'));
        }

        $student->fill($request->all());
        $student->save();

        Flash::success('Student updated successfully.');

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
            return redirect(route('students.index'));
        }
        $student->delete();

        Flash::success('Student deleted successfully.');

        return redirect(route('students.index'));
    }

    public function submit_exam_result(Request $request){
        $student = Student::find($request->studentId);
        $student->exam_status = $request->examResult;
        $student->status ='Waiting for District Admin Approval';
        $student->save();
        return response()->json([
            'success' => true,
            'message' => "Result submitted successfully",
            'data' => $student
        ]);
    }
    public function forward_to_chairman($studentId){
        $student = Student::find($studentId);
        $student->districts_admin_status = "Approved";
        $student->districts_admin_id = auth()->user()->id;
        $student->status ='Waiting for Chairman Approval';
        $student->save();
        Flash::success('Student forwarded to Chairman successfully.');

        return back();
        }
    public function chairman_approve($studentId){
        $student = Student::find($studentId);
        $student->chairmen_status = "Approved";
        $student->chairmen_id = auth()->user()->id;
        $student->status ='Chairman Approved';
        $student->save();
        Flash::success('Operation completed successfully.');
        return back();
    }
    public function generate_certificate($studentId){
        $student = Student::select('students.*', 'districts.name_en as district', 'occupations.title as occupation')
            ->join('districts', 'students.district_id', '=', 'districts.id')
            ->join('occupations', 'students.occupation_id', '=', 'occupations.id')
            ->orderBy('id', 'desc')
            ->where('students.id', $studentId)
            ->first();

        return view('students.generate_certificate')->with('student', $student);
    }
}
