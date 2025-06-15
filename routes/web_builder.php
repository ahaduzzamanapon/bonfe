<?php




Route::resource('siteSettings', 'SiteSettingController');
Route::resource('users', 'UserController');
Route::resource('permissions', 'PermissionController');
Route::resource('roleAndPermissions', 'RoleAndPermissionController');
Route::resource('designations', 'DesignationController');


Route::resource('districts', 'DistrictController');

Route::resource('chairmen', 'ChairmanController');

Route::resource('students', 'StudentController');
Route::resource('general_students', 'StudentController');

Route::resource('occupations', 'OccupationController');

Route::resource('assessmentVenues', 'AssessmentVenueController');

Route::resource('assessmentCenters', 'AssessmentCenterController');

Route::resource('programs', 'ProgramController');

Route::resource('upazilas', 'UpazilaController');

Route::resource('competences', 'CompetenceController');