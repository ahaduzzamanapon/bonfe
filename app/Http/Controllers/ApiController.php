<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use App\Models\AssessmentCenter;
use App\Models\Student;
use App\Models\District;
use App\Models\Insatitute;
use Illuminate\Http\Request;

class ApiController extends Controller
{
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
                $district_data = $district->toArray();
                unset($district_data['created_at']);
                unset($district_data['updated_at']);
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
    public function get_learner(){
        try {
            $training_center = Insatitute::all()->map(function ($training_center) {
                $district = District::find($training_center->district);
                $district_data = $district->toArray();
                unset($district_data['created_at']);
                unset($district_data['updated_at']);
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
}
