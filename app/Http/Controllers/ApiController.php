<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use App\Models\AssessmentCenter;
use App\Models\Student;
use App\Models\District;
use App\Models\Insatitute;
use App\Models\Upazila;
use App\Models\AssessmentVenue;
use App\Models\Chairman;
use App\Models\Program;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $filterDate;

    public function __construct()
    {
        $this->filterDate = '2025-12-14';
    }

    public function index()
    {
        return response()->json(['message' => 'API is working'], 200);
    }
    public function get_occupations(){
        try {
            $occupations = Occupation::all()->map(function ($occupation) {
                return [
                    'id' => $occupation->id,
                    'name' => $occupation->title,
                ];
            })->toArray();

            return response()->json(['success' => true, 'data' => $occupations], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function get_training_center(){
        try {
            $training_center = Insatitute::all()->map(function ($training_center) {
                $district = District::find($training_center->district);
                $district_data = null;
                if ($district) {
                    $district_data = $district->toArray();
                    unset($district_data['created_at']);
                    unset($district_data['updated_at']);
                }
                return [
                    'id' => $training_center->id,
                    'insatitute_name' => $training_center->insatitute_name,
                    'district' => $district_data,
                    'address' => $training_center->address,
                    'status' => $training_center->status,
                ];
            })->toArray();

            return response()->json(['success' => true, 'data' => $training_center], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    private function transformStudent($student)
    {
        $occupation = Occupation::find($student->occupation_id);
        $occupation_data = null;
        if ($occupation) {
            $occupation_data = $occupation->toArray();
            unset($occupation_data['created_at']);
            unset($occupation_data['updated_at']);
        }

        $training_center = Insatitute::find($student->institutionName);
        $training_center_data = null;
        if ($training_center) {
            $training_center_data = $training_center->toArray();
            unset($training_center_data['created_at']);
            unset($training_center_data['updated_at']);
        }

        $district = District::find($student->district_id);
        $district_data = null;
        if ($district) {
            $district_data = $district->toArray();
            unset($district_data['created_at']);
            unset($district_data['updated_at']);
        }

        $upazila = Upazila::find($student->upajila_id);
        $upazila_data = null;
        if ($upazila) {
            $upazila_data = $upazila->toArray();
            unset($upazila_data['created_at']);
            unset($upazila_data['updated_at']);
        }

        $center = AssessmentCenter::find($student->assessment_center);
        $center_data = null;
        if ($center) {
            $center_data = $center->toArray();
            unset($center_data['created_at']);
            unset($center_data['updated_at']);
        }

        $chairman = Chairman::find($student->chairmen_id);
        $chairman_data = null;
        if ($chairman) {
            $chairman_data = $chairman->toArray();
            unset($chairman_data['created_at']);
            unset($chairman_data['updated_at']);
        }

        $program = Program::find($student->program_id);
        $program_data = null;
        if ($program) {
            $program_data = $program->toArray();
            unset($program_data['created_at']);
            unset($program_data['updated_at']);
        }

        $student_data = $student->toArray();
        unset($student_data['created_at']);
        unset($student_data['updated_at']);
        $student_data['occupation'] = $occupation_data;
        $student_data['district'] = $district_data;
        $student_data['upazila'] = $upazila_data;
        $student_data['assessment_center'] = $center_data;
        $student_data['training_center'] = $training_center_data;
        $student_data['chairman'] = $chairman_data;
        $student_data['program'] = $program_data;

        unset($student_data['occupation_id']);
        unset($student_data['district_id']);
        unset($student_data['upajila_id']);
        unset($student_data['chairmen_id']);
        unset($student_data['program_id']);
        unset($student_data['institution_no_temp']);
        unset($student_data['assessment_venue']);
        unset($student_data['institutionName']);
        return $student_data;
    }

    public function get_learner(Request $request){
        try {
            $query = Student::query()->whereDate('created_at', '>=', $this->filterDate);

            if ($request->all) {
                $learners = $query->get()->map(function ($student) {
                    return $this->transformStudent($student);
                });
                return response()->json(['success' => true, 'data' => $learners], 200);
            }

            $perPage = $request->per_page ?? 15;
            $learners = $query->paginate($perPage);

            $learners->getCollection()->transform(function ($student) {
                return $this->transformStudent($student);
            });

            return response()->json(['success' => true, 'data' => $learners], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function get_programs(){
        try {
            $programs = Program::all()->map(function ($program) {
                return [
                    'id' => $program->id,
                    'program_title' => $program->program_title,
                    'program_type' => $program->program_type,
                ];
            })->toArray();

            return response()->json(['success' => true, 'data' => $programs], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function get_districts(){
        try {
            $districts = District::all()->map(function ($district) {
                return [
                    'id' => $district->id,
                    'name_en' => $district->name_en,
                    'name_bn' => $district->name_bn,
                ];
            })->toArray();

            return response()->json(['success' => true, 'data' => $districts], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function get_upazilas(){
        try {
            $upazilas = Upazila::all()->map(function ($upazila) {
                return [
                    'id' => $upazila->id,
                    'name_en' => $upazila->name_en,
                    'name_bn' => $upazila->name_bn,
                ];
            })->toArray();

            return response()->json(['success' => true, 'data' => $upazilas], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function get_upazila_by_district(Request $request){
        try {
            $upazilas = Upazila::where('dis_id', $request->district_id)->get()->map(function ($upazila) {
                return [
                    'id' => $upazila->id,
                    'name_en' => $upazila->name_en,
                    'name_bn' => $upazila->name_bn,
                ];
            })->toArray();

            return response()->json(['success' => true, 'data' => $upazilas], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function get_training_center_by_district_id(Request $request){
        try {
            $training_centers = Insatitute::where('district', $request->district_id)->get()->map(function ($training_center) {
                $district = District::find($training_center->district);
                $district_data = null;
                if ($district) {
                    $district_data = $district->toArray();
                    unset($district_data['created_at']);
                    unset($district_data['updated_at']);
                }
                return [
                    'id' => $training_center->id,
                    'insatitute_name' => $training_center->insatitute_name,
                    'district' => $district_data,
                    'address' => $training_center->address,
                    'status' => $training_center->status,
                ];
            })->toArray();

            return response()->json(['success' => true, 'data' => $training_centers], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function get_learner_by_district(Request $request){
        try {
            $query = Student::where('district_id', $request->district_id)->whereDate('created_at', '>=', $this->filterDate);

            $learners = $query->get()->map(function ($student) {
                return $this->transformStudent($student);
            })->toArray();

            return response()->json(['success' => true, 'data' => $learners], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function get_learner_by_upazila(Request $request){
        try {
            $query = Student::where('upajila_id', $request->upazila_id)->whereDate('created_at', '>=', $this->filterDate);

            $learners = $query->get()->map(function ($student) {
                return $this->transformStudent($student);
            })->toArray();

            return response()->json(['success' => true, 'data' => $learners], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}

