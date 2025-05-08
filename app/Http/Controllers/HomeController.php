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

    public function getDashboardData()
    {
        $query = DB::table('students');

        if (!can('chairman') && can('district_admin')) {
            $query = $query->where('students.district_id', auth()->user()->district_id);
        }

        $total_students = $query->count();

        $passedQuery = DB::table('students')->where('exam_status', 'Passed');
        if (!can('chairman') && can('district_admin')) {
            $passedQuery = $passedQuery->where('students.district_id', auth()->user()->district_id);
        }
        $total_passed_students = $passedQuery->count();

        $total_failed_students = $total_students - $total_passed_students;

        $waiting_for_chairman = DB::table('students')
            ->where('exam_status', 'Passed')
            ->where('status', 'Waiting for Chairman Approval');
        if (!can('chairman') && can('district_admin')) {
            $waiting_for_chairman = $waiting_for_chairman->where('students.district_id', auth()->user()->district_id);
        }
        $waiting_for_chairman = $waiting_for_chairman->count();

        $waiting_for_district = DB::table('students')
            ->where('exam_status', 'Passed')
            ->where('status', 'Waiting for District Admin Approval');
        if (!can('chairman') && can('district_admin')) {
            $waiting_for_district = $waiting_for_district->where('students.district_id', auth()->user()->district_id);
        }
        $waiting_for_district = $waiting_for_district->count();

        $generated_certificate = DB::table('students')
            ->where('exam_status', 'Passed')
            ->where('status', 'Chairman Approved');

        if (!can('chairman') && can('district_admin')) {
            $generated_certificate = $generated_certificate->where('students.district_id', auth()->user()->district_id);
        }
        $generated_certificate = $generated_certificate->count();

        return response()->json([
            'total_students' => $total_students,
            'total_passed_students' => $total_passed_students,
            'total_failed_students' => $total_failed_students,
            'waiting_for_chairman' => $waiting_for_chairman,
            'waiting_for_district' => $waiting_for_district,
            'generated_certificate' => $generated_certificate
        ]);
    }
}
