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
    return redirect('https://www.hixiaogan.cn');
});

Route::any('/weChat', 'WeChatController@server');

Route::group(['middleware' => ['web'], 'prefix' => 'api'], function () {
    Route::get('logs/' . env('ACCESS_LOG_TOKEN'), '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

Route::group(['middleware' => ['web'], 'prefix' => 'music'], function () {
    Route::get('detail', 'MusicController@list');
});


