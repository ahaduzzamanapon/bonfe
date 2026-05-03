<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;


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



Route::get('/getStudentsjson', [StudentController::class, 'getStudentsjson']);



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
    Route::post('give_candidate_id_submit', 'StudentController@give_candidate_id_submit')->name('give_candidate_id_submit');
    Route::post('give_certificate_number_submit', 'StudentController@give_certificate_number_submit')->name('give_certificate_number_submit');
    Route::get('forward_to_chairman/{id}', 'StudentController@forward_to_chairman')->name('students.forward_to_chairman');
    Route::get('chairman_approve/{id}', 'StudentController@chairman_approve')->name('students.chairman_approve');
    Route::get('/students/{id}/generate-certificate', 'StudentController@generate_certificate')->name('students.generate_certificate');



    Route::get('students_waiting_for_district_approval', 'StudentController@students_waiting_for_district_approval')->name('students.students_waiting_for_district_approval');
    Route::get('students_back_to_district_approval', 'StudentController@students_back_to_district_approval')->name('students.students_back_to_district_approval');
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

    Route::get('backToDistrict_modal', 'StudentController@backToDistrict_modal')->name('backToDistrict_modal');
    Route::post('backToDistrict_send', 'StudentController@backToDistrict_send')->name('backToDistrict_send');
    
    Route::get('approveStudent_modal', 'StudentController@approveStudent_modal')->name('approveStudent_modal');
    Route::post('approveStudent_send', 'StudentController@approveStudent_send')->name('approveStudent_send');

    Route::get('generateCertificate_modal', 'StudentController@generateCertificate_modal')->name('generateCertificate_modal');
    Route::get('generateCertificate_send', 'StudentController@generateCertificate_send')->name('generateCertificate_send');
    Route::get('get_competences_by_occupation', 'StudentController@get_competences_by_occupation')->name('get_competences_by_occupation');
    Route::get('viewResult', 'StudentController@viewResult')->name('viewResult');
    Route::get('get_candidate_number_preview', 'StudentController@get_candidate_number_preview')->name('students.get_candidate_number_preview');

    Route::get('/dashboard-data', [HomeController::class, 'getDashboardData'])->name('dashboard.data');

    // Student Import Routes
    Route::get('students_import', 'StudentController@import_students_page')->name('students.import_page');
    Route::post('students_import_preview', 'StudentController@import_students_preview')->name('students.import_preview');
    Route::post('students_import_store', 'StudentController@import_students_store')->name('students.import_store');
    Route::get('students_import_sample', 'StudentController@download_import_sample')->name('students.import_sample');
    Route::get('get_institutes_by_type', 'InsatituteController@get_institutes_by_type')->name('get_institutes_by_type');

    // District Admin: Set Assessment Status (Ready for Assessment / Dropout / Absent)
    Route::get('setAssessmentStatus_modal', 'StudentController@setAssessmentStatus_modal')->name('setAssessmentStatus_modal');
    Route::post('setAssessmentStatus_send', 'StudentController@setAssessmentStatus_send')->name('setAssessmentStatus_send');

    // District Admin: Forward to Assistant Registrar
    Route::get('forwardToAssistantRegistrar_modal', 'StudentController@forwardToAssistantRegistrar_modal')->name('forwardToAssistantRegistrar_modal');
    Route::post('forwardToAssistantRegistrar_send', 'StudentController@forwardToAssistantRegistrar_send')->name('forwardToAssistantRegistrar_send');

    // Assistant Registrar: Give Registration Number
    Route::get('giveRegistrationNumber_modal', 'StudentController@giveRegistrationNumber_modal')->name('giveRegistrationNumber_modal');
    Route::post('giveRegistrationNumber_approve', 'StudentController@giveRegistrationNumber_approve')->name('giveRegistrationNumber_approve');

    // Registration Card
    Route::get('students/{id}/registration-card', 'StudentController@registrationCard')->name('students.registration_card');

    // ── Reports ──────────────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'ReportController@index')->name('index');
        Route::get('/project-wise', 'ReportController@projectWise')->name('project_wise');
        Route::get('/district-wise', 'ReportController@districtWise')->name('district_wise');
        Route::get('/upazila-wise', 'ReportController@upazilaWise')->name('upazila_wise');
        Route::get('/gender-wise', 'ReportController@genderWise')->name('gender_wise');
        Route::get('/occupation-wise', 'ReportController@occupationWise')->name('occupation_wise');
        Route::get('/student-id', 'ReportController@studentId')->name('student_id');
        Route::get('/certificate-distribution', 'ReportController@certificateDistribution')->name('certificate_distribution');
        Route::get('/nyc-students', 'ReportController@nycStudents')->name('nyc_students');
        Route::get('/export-excel', 'ReportController@exportExcel')->name('export_excel');
        Route::get('/export-pdf', 'ReportController@exportPdf')->name('export_pdf');
    });

    // ── Re-Assessment ─────────────────────────────────────────────────────────
    Route::prefix('reassessments')->name('reassessments.')->group(function () {
        Route::get('/', 'ReassessmentController@index')->name('index');
        Route::post('/apply', 'ReassessmentController@apply')->name('apply');
        Route::post('/schedule', 'ReassessmentController@schedule')->name('schedule');
        Route::post('/enter-result', 'ReassessmentController@enterResult')->name('enter_result');
        Route::post('/chairman-approve', 'ReassessmentController@chairmanApprove')->name('chairman_approve');
        Route::get('/{id}/certificate', 'ReassessmentController@generateCertificate')->name('certificate');
    });

    // ── Certificate Corrections ───────────────────────────────────────────────
    Route::prefix('certificate-corrections')->name('certificate_corrections.')->group(function () {
        Route::get('/', 'CertificateCorrectionController@index')->name('index');
        Route::get('/create/{studentId}', 'CertificateCorrectionController@create')->name('create');
        Route::post('/store', 'CertificateCorrectionController@store')->name('store');
        Route::get('/versions/{studentId}', 'CertificateCorrectionController@versions')->name('versions');
        Route::get('/{id}', 'CertificateCorrectionController@show')->name('show');
        Route::post('/{id}/controller-approve', 'CertificateCorrectionController@controllerApprove')->name('controller_approve');
        Route::post('/{id}/chairman-approve', 'CertificateCorrectionController@chairmanApprove')->name('chairman_approve');
    });

    // ── Audit Logs ────────────────────────────────────────────────────────────
    Route::prefix('audit-logs')->name('audit_logs.')->group(function () {
        Route::get('/', 'AuditLogController@index')->name('index');
        Route::get('/{id}', 'AuditLogController@show')->name('show');
    });

});
Route::get('empty_table', 'JoshController@emptyTable');
Route::get('remove_all_files', 'JoshController@remove_all_files');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('{name?}', 'JoshController@showView');

Route::get('qr_details/{id}', 'StudentController@qr_details')->name('students.qr_details');


Route::get('/upload_exell', function () {
    return view('upload_exell');
});

Route::post('/import-users', [StudentController::class, 'import'])->name('import.users');

Route::get('/upload_users', [UserController::class, 'upload_users_page'])->name('users.upload_page');
Route::post('/users/import', [UserController::class, 'import_users'])->name('users.import');



