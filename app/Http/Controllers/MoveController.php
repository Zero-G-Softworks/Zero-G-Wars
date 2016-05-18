<?php

namespace ZeroGWars\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use ZeroGWars\Http\Controllers\Controller;
use ZeroGWars\Move;
use ZeroGWars\ShipType;
use ZeroGWars\Game;
use ZeroGWars\GameState;
use ZeroGWars\Utilities\GameUtilities;

class MoveController extends Controller
{
    
    private $game = null;
    private $game_state = null;
    private $board = null;
    private $ships = null;
    
    /**
     * Initialize the current game state and member variables.
     * 
     * @param  int  $id
     */
    public function initialize($id, $nomoves = false)
    {
        $this->game = Game::where('public_id', '=', $id)->firstOrFail();
        if( $this->game->game_state === null ) {
            $this->board = array();
            $this->board["p1"] = array();
            $this->board["p2"] = array();
            
            $this->ships = array();
            $this->ships["p1"] = array();
            $this->ships["p2"] = array();
            
            $this->game_state = new GameState;
            $this->game_state->board = json_encode($this->board);
            $this->game_state->ships = json_encode($this->ships);
            $this->game_state->save();
            
            $this->game->game_state = $this->game_state->id;
            $this->game->save();
        }
        else {
            $this->game_state = GameState::find($this->game->game_state);
            $this->board = json_decode($this->game_state->board);
            $this->ships = json_decode($this->game_state->ships);
        }
        print_r($this->board);
        print_r($this->ships);
        $sane = $this->sanity_check($nomoves);
        if( $sane !== true ) { return ['error' => $sane]; }
        else { return [true]; }
    }

    /**
     * Place the ship on the board based on a starting tile and a rotation.
     *
     * @param  int     $id
     * @param  int     $ship
     * @param  string  $from
     * @param  int     $rotation
     * @return Response
     */
    public function put($id, $ship, $from, $rotation)
    {
        $init = $this->initialize($id, true);
        if( $init !== [true] ) { return $init; }
        
        if( GameUtilities::isOutOfBounds($from, $this->game->getEndTile()) ) {
            return ['error' => 'tile out of bounds'];
        }
        
        $type = ShipType::where('id', '=', $ship)->firstOrFail();
        $rotation = strtolower($rotation);
        list($colTo, $rowTo) = list($col, $row) = GameUtilities::tileToNum($from);
        
        switch($rotation) {
            case 'down':
                $rowTo = $row + $type->length - 1;
                break;
            case 'right':
                $colTo = $col + $type->length - 1;
                break;
            case 'up':
                $rowTo = $row - $type->length + 1;
                break;
            case 'left':
                $colTo = $col - $type->length + 1;
                break;
            default:
                return ['error' => 'invalid direction: '.$rotation];
        }
        
        if( GameUtilities::isOutOfBounds(GameUtilities::numToTile($colTo, $rowTo), $this->game->getEndTile()) ) {
            return ['error' => 'tile out of bounds'];
        }
        
        $move = new Move;
        $move->user_id = Auth::user()->id;
        $move->game_id = $this->game->id;
        $move->action = 'put';
        $move->source_tile = $from;
        $move->ship_type = $type->id;
        $move->target_tile = GameUtilities::numToTile($colTo, $rowTo);
        $move->save();
        
        
        
        return $move;
    }

    /**
     * Attempt to hit a tile.
     *
     * @param  int    $id
     * @param  string $tile
     * @return Response
     */
    public function hit($id, $tile)
    {
        $init = $this->initialize($id);
        if( $init !== [true] ) { return $init; }
        
        if( GameUtilities::isOutOfBounds($tile, $this->game->getEndTile()) ) {
            return ['error' => 'tile out of bounds'];
        }
        
        $move = new Move;
        $move->user_id = Auth::user()->id;
        $move->game_id = $this->game->id;
        $move->action = 'hit';
        $move->target_tile = $tile;
        $move->save();
        return [$move];
    }

    /**
     * Verify that the game meets specific criteria for a valid game.
     * 
     * Return values:
     *  * valid game, waiting for player 2
     *  * valid game, waiting for all ships to be placed
     *  * valid game, no winner
     *  * invalid game
     *      -> game doesn't exist
     *      -> game has moves, but all ships weren't placed
     *
     * @param  int  $id
     * @param  bool $nomoves
     * @return Response
     */
    public function sanity_check($id, $nomoves = false)
    {
        if( $this->game === null ) {
            $init = $this->initialize($id, $nomoves);
            if( $init === [true] ) { return true; }
            else { return $init; }
        }
        if( !Auth::check() ) { return 'log in'; }
        if(Auth::user()->id != $this->game->player1 && Auth::user()->id != $this->game->player2) { return "access denied"; }
        if($this->game->player2 == null) { return "awaiting second player"; }
        
        if( $nomoves ) { return true; }
        /* verify that all ships have been placed
         * $moves = Move::where('game_id', '=', $this->game->id)
         *              ->where('action', '=', '1');
         */
        return true;
    }
}
