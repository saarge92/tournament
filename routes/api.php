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

Route::post('/qualification/generate', [
    'uses' => 'QualificationGeneratorController@generateQualificationGames'
]);

Route::get('/qualification/tournament/{id}', [
    'uses' => 'QualificationController@getQualificationByTournamentId'
]);

Route::post('/playoff/tournament/{id}/generate',[
    'uses' => 'PlayOffGeneratorController@generatePlayOff'
]);

Route::get('/playoff/tournament/{id}',[
    'uses' => 'PlayOffController@getPlayOffInfo'
]);
