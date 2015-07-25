<?php

namespace ZeroGWars\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;

use ZeroGWars\Http\Requests;
use ZeroGWars\Http\Controllers\Controller;

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
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
        
        return [ 'game_id' => $game->public_id, 'player1' => $game->player1, 'player2' => $game->player2 ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
