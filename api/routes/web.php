<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::resource('/user', 'UserController');
Route::resource('/factor', 'FactorController');
Route::resource('/question', 'QuestionController');
Route::resource('/answer', 'AnswerController');
Route::resource('/institution', 'InstitutionController');
Route::resource('/program', 'ProgramController');


Route::post('/factor/change_status/{id}', 'FactorController@change_status');
Route::get('/factor/{id}/questions','FactorController@questions');

Route::get('/user/{id}/response','UserController@factor_response');
Route::get('/user/{id}/statistics', 'UserController@statistics');
Route::get('/today-question-availability/{id}','Factor_Secundary_TimeController@check_date');

Route::get('/question/today/{id}', 'QuestionController@show_today');
Route::post('/answer/today', 'AnswerController@store_today_question');
	
/*Reportes*/
	Route::get('/report','ReportController@index');
	Route::post('/report/general-by-total/{type}','ReportController@create_general_by_total');
	Route::post('/report/general-by-question','ReportController@create_general_by_question');
	Route::post('/report/by-factor','ReportController@create_by_factor');
	Route::post('/report/final_instrument', 'ReportController@create_final_instrument');

	Route::post('/report/students-progress', 'ReportController@create_students_progress');
	Route::post('/report/students-institution-progress', 'ReportController@create_students_institution_progress');
	Route::post('/report/students-program-progress', 'ReportController@create_students_program_progress');
/*Reportes*/

Route::post('/auth', 'UserController@check');
Route::get('/home', 'UserController@home');


	//De prueba
Route::get('/test','UserController@test');
