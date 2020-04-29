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

  Route::get('getListWorks', 'ApiWorkController@getListWorks');
  Route::get('getListTasks', 'ApiWorkController@getListTasks');
  Route::get('getListTasksByUser', 'ApiWorkController@getListTasksByUser');
  
  Route::post('addNote', 'ApiNoteController@add');
});
