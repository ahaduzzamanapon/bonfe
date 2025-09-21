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

// user table  id,name,last_name,email,designation_id,district_id,date_of_birth,date_of_join,gender,address,phone_number,image,signature,salary,nid,group_id,assessment_center,occupation,education,blood_group,religion,marital_status,punch_id,emp_id,experience,email_verified_at,password,remember_token,created_at,updated_at
// students table id,program_id,occupation_id,registration_number,candidate_id,candidate_name,candidate_name_bn,brn,father_name,mother_name,image,attachment,nid,district_id,upajila_id,address,date_of_birth,mobile_number,email,admitted_from,institutionName,assessment_date,assessment_venue,assessment_center,assessment_center_registration_number,age,literacy_status,educational_qualification,training_start_date,training_end_date,gender,status,exam_status,exam_result_sheet,chairmen_id,chairmen_status,controller_id,districts_admin_id,districts_admin_status,controller_back_comments,notified,created_at,updated_at


// sample of filter 

// if(can('filtered_by_multi_district')){
//     student.where('district_id', auth()->user()->district_id);
// }






        $program_type = DB::table('programs')->where('id', $program_id)->first()->program_type;

        $studentsQuery = DB::table('students')
            ->where('program_id', $program_id);

        if ($occupation_id) {
            $studentsQuery->where('occupation_id', $occupation_id);
        }

     if (!can('get_all_student')) {
        
        if (can('filtered_by_multi_district')) {
            $userDistricts = MultipleDistrict::where('user_id', auth()->user()->id)
                ->pluck('district_id')
                ->toArray();
            $studentsQuery->whereIn('district_id', $userDistricts);
        }
        if (can('filtered_by_own_district')) {
            $studentsQuery->where('district_id', auth()->user()->district_id);
        }
        
        if (can('filtered_by_own_district')) {
            $studentsQuery->where('district_id', auth()->user()->district_id);
        }

        if (can('filtered_by_own_tread')) {
            $studentsQuery->where('occupation_id', auth()->user()->occupation);
        }
        if (can('filtered_by_own_center')) {
            $studentsQuery->where('occupation_id', auth()->user()->assessment_center);
        }
    }
        $total_students = $studentsQuery->count();
        $total_passed_students = $studentsQuery->where('exam_status', 'Passed')->count();
        $total_failed_students = $studentsQuery->where('exam_status', 'Fail')->count();
        $waiting_for_chairman = $studentsQuery->where('status', 'Waiting for Chairman Approval')->count();
        $waiting_for_district = $studentsQuery->where('status', 'Waiting for District Admin Approval')->count();
        $generated_certificate = $studentsQuery->where('status', 'Chairman Approved')->count();
        $waiting_for_assessment_center = $studentsQuery->where('status', 'Waiting for the exam results from the Assessment Center')->count();
        $waiting_for_assessment_controller = $studentsQuery->where('status', 'Waiting for Assessment Controller Approval')->count();
        return response()->json([
            'total_students' => $total_students,
            'total_passed_students' => $total_passed_students,
            'total_failed_students' => $total_failed_students,
            'waiting_for_chairman' => $waiting_for_chairman,
            'waiting_for_district' => $waiting_for_district,
            'generated_certificate' => $generated_certificate,
            'waiting_for_assessment_center' => $waiting_for_assessment_center,
            'waiting_for_assessment_controller' => $waiting_for_assessment_controller,
            'program_type' => $program_type
        ]);
    }
}
