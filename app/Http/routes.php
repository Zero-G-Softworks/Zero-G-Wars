<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function() {
    return view('welcome');
});
    
Route::get('/game/{id}', function() {
    return view("welcome");
});

/*
 * TODO: GET  game/:id gets current game information (spectator mode)
 *       PUSH game/:id joins game if player spot is available
 *       if PUSH game/:id finds existing player2, it will return 403 forbidden and client will GET game/:id
 */

Route::group(['prefix' => '/api/v1'], function () {
    Route::get('/', function() {
        return 'api home';
    });
    Route::group(['prefix' => '/game'], function() {
        Route::post('/', 'GameController@create');
        Route::get('/', 'GameController@index');
        Route::get('/{id}', 'GameController@show');
        /*
        Route::push('/{id}','GameController@join'); // Player 2 joins
        Route::push('/{id}/attack/{tile}','GameController@attack'); // Player attacks a tile on the board
        Route::get('/{id}/update/{timestamp}','GameController@update'); // Get moves since last check -- optimization to not pull all game data for each update
                                                                        // does a 'where move timestamp is greater than {timestamp}'
        */
    });
    
    Route::post('/auth/login', 'Auth\AuthController@authenticate');
    Route::post('/auth/register', 'Auth\AuthController@createAJAX');
    Route::post('/auth/logout', function() {
        Auth::logout();
    });
});