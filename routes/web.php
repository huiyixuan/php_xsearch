<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return '<h3>hello, ' . get_client_ip() . '</h3>';
});

Route::get('ping', function () {
    return json_encode([
        "code" => 200,
        "msg" => "pong",
        "data" => json_decode('{}'),
    ], JSON_UNESCAPED_UNICODE);
});

Route::group(["prefix" => 'stat'], function () {
    Route::get("jog", '\App\Http\Controllers\Stat\FitStatController@jog');
});


Route::group(["prefix" => 'resource'], function () {
    Route::post("store", '\App\Http\Controllers\Resource\UploadController@store');
});
