<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::resource('events', \App\Http\Controllers\EventController::class);

Route::post('search_event',[\App\Http\Controllers\EventController::class,'search_event']);

Route::get('generate_pdf/{start_date}/{end_date}',[\App\Http\Controllers\EventController::class,'generate_pdf']);
