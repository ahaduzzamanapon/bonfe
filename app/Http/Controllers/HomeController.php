<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Upazila;
use Illuminate\Support\Facades\DB;
use App\Models\District;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function items_dashboard(){
        $items=Item::all();

        
        return view('items.item_dashboard',compact('items'));
    }

    public function get_upazilas(Request $request){

     

        $upazilas = Upazila::where('dis_id', $request->district_id)->get(['id', 'name_en as name']);
        if ($upazilas->isEmpty()) {
            return response()->json(['message' => 'No upazilas found for the given district ID.'], 404);
        }
        return response()->json($upazilas);
    }

    public function getDashboardData(Request $request)
    {

        $program_id = $request->program_id;
        $occupation_id = $request->occupation_id;

        $query = DB::table('students');

        if (!can('chairman') && can('district_admin')) {
            $query = $query->where('students.district_id', auth()->user()->district_id);
        }

        if (!can('chairman') && !can('district_admin') && can('assessment_centers_controller')) {
            $query = $query->where('students.assessment_center', auth()->user()->assessment_center)->where('students.occupation_id', auth()->user()->occupation);
        }         

        if ($program_id) {
            $query = $query->where('students.program_id', $program_id);
        }
        if ($occupation_id) {
            $query = $query->where('students.occupation_id', $occupation_id);
        }

        $total_students = $query->count();

        $passedQuery = DB::table('students')->where('exam_status', 'Passed');
        if (!can('chairman') && can('district_admin')) {
            $passedQuery = $passedQuery->where('students.district_id', auth()->user()->district_id);
        }

        if (!can('chairman') && !can('district_admin') && can('assessment_centers_controller')) {
            $passedQuery = $passedQuery->where('students.assessment_center', auth()->user()->assessment_center)->where('students.occupation_id', auth()->user()->occupation);
        } 




        if ($program_id) {
            $passedQuery = $passedQuery->where('students.program_id', $program_id);
        }
        if ($occupation_id) {
            $passedQuery = $passedQuery->where('students.occupation_id', $occupation_id);
        }
        $total_passed_students = $passedQuery->count();




        $failQuery = DB::table('students')->where('exam_status', 'Fail');
        if (!can('chairman') && can('district_admin')) {
            $failQuery = $failQuery->where('students.district_id', auth()->user()->district_id);
        }

        if (!can('chairman') && !can('district_admin') && can('assessment_centers_controller')) {
            $failQuery = $failQuery->where('students.assessment_center', auth()->user()->assessment_center)->where('students.occupation_id', auth()->user()->occupation);
        } 

        if ($program_id) {
            $failQuery = $failQuery->where('students.program_id', $program_id);
        }
        if ($occupation_id) {
            $failQuery = $failQuery->where('students.occupation_id', $occupation_id);
        }
        $total_failed_students = $failQuery->count();





        $waiting_for_chairman = DB::table('students')
            ->where('exam_status', 'Passed')
            ->where('status', 'Waiting for Chairman Approval');
        if (!can('chairman') && can('district_admin')) {
            $waiting_for_chairman = $waiting_for_chairman->where('students.district_id', auth()->user()->district_id);
        }
        if (!can('chairman') && !can('district_admin') && can('assessment_centers_controller')) {
            $waiting_for_chairman = $waiting_for_chairman->where('students.assessment_center', auth()->user()->assessment_center)->where('students.occupation_id', auth()->user()->occupation);
        }
        if ($program_id) {
            $waiting_for_chairman = $waiting_for_chairman->where('students.program_id', $program_id);
        }
        if ($occupation_id) {
            $waiting_for_chairman = $waiting_for_chairman->where('students.occupation_id', $occupation_id);
        }
        $waiting_for_chairman = $waiting_for_chairman->count();

        $waiting_for_district = DB::table('students')
            ->where('exam_status', 'Passed')
            ->where('status', 'Waiting for District Admin Approval');
        if (!can('chairman') && can('district_admin')) {
            $waiting_for_district = $waiting_for_district->where('students.district_id', auth()->user()->district_id);
        }
        if (!can('chairman') && !can('district_admin') && can('assessment_centers_controller')) {
            $waiting_for_district = $waiting_for_district->where('students.assessment_center', auth()->user()->assessment_center)->where('students.occupation_id', auth()->user()->occupation);
        }
        if ($program_id) {
            $waiting_for_district = $waiting_for_district->where('students.program_id', $program_id);
        }
        if ($occupation_id) {
            $waiting_for_district = $waiting_for_district->where('students.occupation_id', $occupation_id);
        }
        $waiting_for_district = $waiting_for_district->count();

        $generated_certificate = DB::table('students')
            ->where('exam_status', 'Passed')
            ->where('status', 'Chairman Approved');

        if (!can('chairman') && can('district_admin')) {
            $generated_certificate = $generated_certificate->where('students.district_id', auth()->user()->district_id);
        }
        if (!can('chairman') && !can('district_admin') && can('assessment_centers_controller')) {
            $generated_certificate = $generated_certificate->where('students.assessment_center', auth()->user()->assessment_center)->where('students.occupation_id', auth()->user()->occupation);
        }
        if ($program_id) {
            $generated_certificate = $generated_certificate->where('students.program_id', $program_id);
        }
        if ($occupation_id) {
            $generated_certificate = $generated_certificate->where('students.occupation_id', $occupation_id);
        }
        $generated_certificate = $generated_certificate->count();

        $program_type = DB::table('programs')->where('id', $program_id)->first()->program_type;






        return response()->json([
            'total_students' => $total_students,
            'total_passed_students' => $total_passed_students,
            'total_failed_students' => $total_failed_students,
            'waiting_for_chairman' => $waiting_for_chairman,
            'waiting_for_district' => $waiting_for_district,
            'generated_certificate' => $generated_certificate,
            'program_type' => $program_type
        ]);
    }
}
