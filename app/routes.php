<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'GameController@getIndex');
Route::get('vote/{game_id}', 'GameController@voteForGame');
Route::post('add-game', 'GameController@addGame');
Route::get('own/{game_id}', 'GameController@ownGame');

Route::get('games-admin', 'AdminController@getIndex');
Route::get('games-admin/clear', 'AdminController@clearGames');

//404 error handling
App::missing(function($exception)
{	   
	return View::make('404');
});


