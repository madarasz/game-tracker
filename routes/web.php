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

Route::get('/', 'GameController@manageGames');
Route::get('/games/{id}', 'GameController@viewGame');
Route::get('/games/{id}/session/{session}', 'GameController@viewGame');
Route::get('/random-mars', 'PageController@marsRandomizer');
Auth::routes();

Route::get('/home', 'HomeController@index');
