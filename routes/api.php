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

Route::resource('games', 'GameController');
Route::resource('game-types', 'GameTypeController');
Route::resource('game-sessions', 'GameSessionController');
Route::resource('game-seasons', 'GameSeasonController');
Route::resource('players', 'PlayerController');
Route::resource('photos', 'PhotoController');
Route::resource('game-factions', 'GameFactionController');

Route::get('game-sessions/game/{gameid}', 'GameSessionController@indexForGame');
Route::get('game-sessions/game/{gameid}/{seasonid}', 'GameSessionController@indexForGame');
Route::get('users', 'PlayerController@indexUsers');
Route::get('user-details/{userid}', 'PlayerController@userDetail');
Route::get('user-details/{userid}/factions/{gameid}', 'PlayerController@userFactions');
Route::get('players/session/{sessionid}', 'PlayerController@indexForSession');
Route::get('photos/session/{sessionid}', 'PhotoController@indexForSession');
Route::get('photos/{id}/rotate/{dir}', 'PhotoController@rotate');
Route::get('game-sessions/{gameid}/clone', 'GameSessionController@cloneSession');
Route::get('game-factions/game/{gameid}', 'GameFactionController@listForGame');

Route::get('game-sessions/{id}/conclude', 'PointController@concludeSession');
Route::get('games/{id}/{seasonid}/ranking', 'PointController@getGameRanking');
Route::get('games/{id}/{seasonid}/ranking/recalculate', 'PointController@recalculateGame');
Route::get('ranking/game/{gameid}/{seasonid}', 'PointController@historyForGame');

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'AuthController@logout');
});