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

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::get('/', function () {
//    return view('index');
//});

//Route::get('programs/{id}', 'ProgramsController@show');
Route::get('programs', 'ProgramsController@index');
Route::get('programs/detail', ['as'=>'detail','uses'=>'ProgramsController@detail']);
Route::post('programs/detail', ['as'=>'detail','uses'=>'ProgramsController@postDetail']);
