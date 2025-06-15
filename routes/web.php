<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\StudentController;


include 'demo.php';
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// login2, register2 pages
Route::view('login2', 'auth.login2');
Route::view('login3', 'auth.login3');
Route::view('register2', 'auth.register2');
Route::view('register3', 'auth.register3');

Route::get('/', function () {
    return view('index');
})->middleware('auth');



// GUI crud builder routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder')->name('io_generator_builder');

    Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate')->name('io_field_template');

    Route::get('relation_field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@relationFieldTemplate')->name('io_relation_field_template');

    Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate')->name('io_generator_builder_generate');

    Route::post('generator_builder/rollback', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@rollback')->name('io_generator_builder_rollback');

    Route::post(
        'generator_builder/generate-from-file',
        '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generateFromFile'
    )->name('io_generator_builder_generate_from_file');

    // Model checking
    Route::post('tableCheck', 'AppBaseController@tableCheck');

    include 'web_builder.php';

    Route::post('submit_exam_result', 'StudentController@submit_exam_result')->name('submit_exam_result');
    Route::get('forward_to_chairman/{id}', 'StudentController@forward_to_chairman')->name('students.forward_to_chairman');
    Route::get('chairman_approve/{id}', 'StudentController@chairman_approve')->name('students.chairman_approve');
    Route::get('generate_certificate/{id}', 'StudentController@generate_certificate')->name('students.generate_certificate');



    Route::get('students_waiting_for_district_approval', 'StudentController@students_waiting_for_district_approval')->name('students.students_waiting_for_district_approval');
    Route::get('students_waiting_for_chairman_approval', 'StudentController@students_waiting_for_chairman_approval')->name('students.students_waiting_for_chairman_approval');
    
    Route::get('general_students_waiting_for_district_approval', 'StudentController@students_waiting_for_district_approval')->name('general_students.students_waiting_for_district_approval');
    Route::get('general_students_waiting_for_chairman_approval', 'StudentController@students_waiting_for_chairman_approval')->name('general_students.students_waiting_for_chairman_approval');


    Route::get('get_upazilas', 'HomeController@get_upazilas')->name('get_upazilas');
    Route::get('get_table', 'StudentController@get_table')->name('students.get_table');
    Route::get('forwardToAssessmentCenter_modal', 'StudentController@forwardToAssessmentCenter_modal')->name('forwardToAssessmentCenter_modal');
    Route::post('forwardToAssessmentCenter_send', 'StudentController@forwardToAssessmentCenter_send')->name('forwardToAssessmentCenter_send');
    
    Route::get('forwardToDistrictAdmin_modal', 'StudentController@forwardToDistrictAdmin_modal')->name('forwardToDistrictAdmin_modal');
    Route::post('forwardToDistrictAdmin_send', 'StudentController@forwardToDistrictAdmin_send')->name('forwardToDistrictAdmin_send');
   
    Route::get('forwardToChairman_modal', 'StudentController@forwardToChairman_modal')->name('forwardToChairman_modal');
    Route::post('forwardToChairman_send', 'StudentController@forwardToChairman_send')->name('forwardToChairman_send');

    Route::get('forwardToAssessmentController_modal', 'StudentController@forwardToAssessmentController_modal')->name('forwardToAssessmentController_modal');
    Route::post('forwardToAssessmentController_send', 'StudentController@forwardToAssessmentController_send')->name('forwardToAssessmentController_send');
    
    Route::get('approveStudent_modal', 'StudentController@approveStudent_modal')->name('approveStudent_modal');
    Route::post('approveStudent_send', 'StudentController@approveStudent_send')->name('approveStudent_send');

    Route::get('generateCertificate_modal', 'StudentController@generateCertificate_modal')->name('generateCertificate_modal');
    Route::get('generateCertificate_send', 'StudentController@generateCertificate_send')->name('generateCertificate_send');
    Route::get('get_competences_by_occupation', 'StudentController@get_competences_by_occupation')->name('get_competences_by_occupation');

    Route::get('/dashboard-data', [HomeController::class, 'getDashboardData'])->name('dashboard.data');

});
Route::get('empty_table', 'JoshController@emptyTable');
Route::get('remove_all_files', 'JoshController@remove_all_files');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('{name?}', 'JoshController@showView');

Route::get('qr_details/{id}', 'StudentController@qr_details')->name('students.qr_details');


