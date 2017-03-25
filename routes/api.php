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
Route::resource('players', 'PlayerController');
Route::resource('photos', 'PhotoController');

Route::get('game-sessions/game/{gameid}', 'GameSessionController@indexForGame');
Route::get('users', 'PlayerController@indexUsers');
Route::get('players/session/{sessionid}', 'PlayerController@indexForSession');
Route::get('photos/session/{sessionid}', 'PhotoController@indexForSession');
Route::get('game-sessions/{gameid}/clone', 'GameSessionController@cloneSession');

Route::get('game-sessions/{id}/conclude', 'PointController@concludeSession');
Route::get('games/{id}/ranking', 'PointController@getGameRanking');
Route::get('games/{id}/ranking/recalculate', 'PointController@recalculateGame');
