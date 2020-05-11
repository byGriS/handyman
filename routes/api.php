<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('user', function (Request $request) {
  return $request->user();
});

Route::namespace('Api')->group(function () {
  Route::get('getlistusers', 'ApiUserController@getListUsers');
  Route::post('resetPass', 'ApiUserController@resetPass');
  Route::post('deleteUser', 'ApiUserController@deleteUser');
  Route::post('addUser', 'ApiUserController@addUser');
  Route::get('getListHandymans', 'ApiUserController@getListHandymans');

  Route::get('getListWorks', 'ApiWorkController@getListWorks');
  Route::get('getListTasks', 'ApiWorkController@getListTasks');
  Route::get('getListTasksByUser', 'ApiWorkController@getListTasksByUser');
  Route::post('changeTaskCapacity', 'ApiWorkController@changeTaskCapacity');
  Route::post('changeTaskEnd', 'ApiWorkController@changeTaskEnd');
  Route::post('changeTaskStandartPeople', 'ApiWorkController@changeTaskStandartPeople');
  Route::post('changeTaskStandartConsumption', 'ApiWorkController@changeTaskStandartConsumption');
  Route::post('addTask','ApiWorkController@addTask');
  Route::post('deleteTask', 'ApiWorkController@deleteTask');
  Route::post('addWork', 'ApiWorkController@addWork');
  Route::post('deleteWork', 'ApiWorkController@deleteWork');
  Route::post('completeTask', 'ApiWorkController@completeTask');
  Route::post('completeWork', 'ApiWorkController@completeWork');

  Route::post('addNote', 'ApiNoteController@add');
  Route::post('changeNotePeople', 'ApiNoteController@changeNotePeople');
  Route::post('changeNoteConsumption', 'ApiNoteController@changeNoteConsumption');
});
