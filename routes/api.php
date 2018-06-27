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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'User\UserController@login');


Route::get('/kandidater', 'Kandidat\KandidatController@index');
Route::get('/kandidater/{id}', 'Kandidat\KandidatController@show');
Route::post('/kandidater', 'Kandidat\KandidatController@store');//create
Route::put('/kandidater/{id}', 'Kandidat\KandidatController@update');//update
Route::delete('/kandidater/{id}', 'Kandidat\KandidatController@destroy');//delete

Route::get('/images', 'Image\ImageController@index');
Route::get('/images/{id}', 'Image\ImageController@show');
Route::post('/images', 'Image\ImageController@store');//create
Route::put('/images/{id}', 'Image\ImageController@update');//update
Route::delete('/images/{id}', 'Image\ImageController@destroy');//delete

Route::get('/cv_poster', 'CV_post\CV_postController@index');
Route::get('/cv_poster/{id}', 'CV_post\CV_postController@show');
Route::post('/cv_poster', 'CV_post\CV_postController@store');//create
Route::put('/cv_poster/{id}', 'CV_post\CV_postController@update');//update
Route::delete('/cv_poster/{id}', 'CV_post\CV_postController@destroy');//delete


