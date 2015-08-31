<?php

namespace ZeroGWars\Http\Controllers;
use Auth;

use ZeroGWars\Http\Controllers\Controller;
use ZeroGWars\User;
use ZeroGWars\ShipType;
use ZeroGWars\Game;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Create a new Game.
     *
     * @return Response
     */
    public function create()
    {
        $game = new Game;
        $game->public_id = uniqid();
        $game->player1 = Auth::user()->id;
        $game->save();
        //Session::save();

        return [ 'game_id' => $game->public_id ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $game = Game::where('public_id', '=', $id)->firstOrFail();
        
        if( ( $game->player2 == NULL ) && ( $game->player1 != Auth::user()->id ) ) {
            $game->player2 = Auth::user()->id;
            $game->save();
            //Session::save();
        }
        
        $player1 = User::findOrFail($game->player1)->username;
        if( $game->player2 !== NULL ) {
            $player2 = User::findOrFail($game->player2)->username;
        }
        else {
            $player2 = NULL;
        }
        
        
        return [ 'game_id' => $game->public_id, 'player1' => $player1, 'player2' => $player2 ];
    }

    /**
     * Retrieve all ship types.
     *
     * @param  int  $id
     * @return Response
     */
    public function shipTypes()
    {
        $types = ShipType::all(['id','name','length']);
        
        return $types;
    }
}
