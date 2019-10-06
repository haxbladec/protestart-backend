<?php

use Illuminate\Http\Request;

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

Route::post('/users/register', 'Auth\RegisterController@register');
Route::post('/users/login', 'Auth\LoginController@login');
Route::get('/arts', 'ArtController@index');
Route::get('/arts/{id}', 'ArtController@get');

Route::middleware('auth:api')->get('/users/is_alive', function(Request $request){
    return response()->json(array("is_alive"=>true), 200);
});

Route::middleware('auth:api')->post('/arts', 'ArtController@create');
Route::middleware('auth:api')->post('/arts/upload', 'ArtController@upload');