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

        $program_type = DB::table('programs')
            ->where('id', $program_id)
            ->value('program_type');

        $studentsQuery = DB::table('students')->where('program_id', $program_id);

        if ($occupation_id) {
            $studentsQuery->where('occupation_id', $occupation_id);
        }

        
        if (!can('get_all_student')) {

            if (can('filtered_by_multi_district')) {
                $userDistricts = MultipleDistrict::where('user_id', auth()->id())
                    ->pluck('district_id')
                    ->toArray();
                $studentsQuery->whereIn('district_id', $userDistricts);
            }

            if (can('filtered_by_own_district')) {
                $studentsQuery->where('district_id', auth()->user()->district_id);
            }

            if (can('filtered_by_own_tread')) {
                $studentsQuery->where('occupation_id', auth()->user()->occupation);
            }

            if (can('filtered_by_own_center')) {
                $studentsQuery->where('assessment_center', auth()->user()->assessment_center);
            }
        }

        // Clone the query before applying extra where conditions
        $total_students = (clone $studentsQuery)->count();
        $total_passed_students = (clone $studentsQuery)->where('exam_status', 'Passed')->count();
        $total_failed_students = (clone $studentsQuery)->where('exam_status', 'LIKE', '%Fail%')->count();
        $total_absent_students = (clone $studentsQuery)->where('exam_status', 'Absent')->count();
        $total_dropout_students = (clone $studentsQuery)->where('exam_status', 'Dropout')->count();

        $waiting_for_chairman = (clone $studentsQuery)->where('status', 'Waiting for Chairman Approval')->count();
        $waiting_for_district = (clone $studentsQuery)->where('status', 'Waiting for District Admin Approval')->count();
        $generated_certificate = (clone $studentsQuery)->where('status', 'Chairman Approved')->count();
        $waiting_for_assessment_center = (clone $studentsQuery)->where('status', 'Waiting for the exam results from the Assessment Center')->count();
        $waiting_for_assessment_controller = (clone $studentsQuery)->where('status', 'Waiting for Assessment Controller Approval')->count();

        return response()->json([
            'total_students' => $total_students,
            'total_passed_students' => $total_passed_students,
            'total_failed_students' => $total_failed_students,
            'waiting_for_chairman' => $waiting_for_chairman,
            'waiting_for_district' => $waiting_for_district,
            'generated_certificate' => $generated_certificate,
            'waiting_for_assessment_center' => $waiting_for_assessment_center,
            'waiting_for_assessment_controller' => $waiting_for_assessment_controller,
            'total_absent_students' => $total_absent_students,
            'total_dropout_students' => $total_dropout_students,
            'program_type' => $program_type
        ]);
    }

}
