<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('api.key')->group( function () {
    Route::get('/', function () {
        return response()->json(['message' => 'API is working'], 200);
    });

    Route::get('/get_occupations', [ApiController::class, 'get_occupations']);
    Route::get('/get_training_center', [ApiController::class, 'get_training_center']);
    Route::get('/get_learner', [ApiController::class, 'get_learner']);
});
