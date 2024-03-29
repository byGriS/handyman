<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

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


//Auth::routes();

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::post('/1896948525:AAHcOXKulESgNKRNZq1_CJvQ3HAsYremVEU/webhook',  'IndexController@telegram_message_webhook');
Route::get('/telegram_notification', 'IndexController@telegram_notification');

Route::get('/checkowenlcoud', 'OwenCloudController@getData');

Route::get('/{any}', 'IndexController@index')->where('any', '.*');



