<?php

namespace ZeroGWars\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use ZeroGWars\Http\Controllers\Controller;
use ZeroGWars\Move;
use ZeroGWars\ShipType;
use ZeroGWars\Game;

class MoveController extends Controller
{
    private $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                               'f', 'g', 'h', 'i', 'j',
                               'k', 'l', 'm', 'n', 'o',
                               'p', 'q', 'r', 's', 't',
                               'u', 'v', 'w', 'x', 'y',
                               'z'
                             );

    /**
     * Convert a number pair to a tile address.
     *
     * @param  int  $col
     * @param  int  $row
     * @return Response
     */
    public function numToTile($col, $row)
    {
        
        return strtoupper($this->alphabet[$col-1]) . $row;
    }

    /**
     * Convert a tile address to a number pair.
     *
     * @param  string  $tile
     * @return Response
     */
    public function tileToNum($tile)
    {
        $tile = strtolower($tile);
        preg_match('/([a-z]+)([0-9]+)/', $tile, $matches);
        $col = 0;
        for( $i = 0; $i < strlen($matches[1]); $i++ ) {
            $charVal = intval( ord($matches[1][$i]) - 96 );
            $decimal = pow(26, strlen($matches[1])-$i-1);
            $col += $charVal * $decimal;
        }
        $row = intval($matches[2]);
        return [$col, $row];
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
        if( !$this->sanity_check($id, true) ) return ['error' => 'failed sanity check'];
        $game = Game::where('public_id', '=', $id)->firstOrFail();
        $type = ShipType::where('id', '=', $ship)->firstOrFail();
        
        $rotation = strtolower($rotation);
        
        list($col, $row) = $this->tileToNum($from);
        list($maxCol, $maxRow) = $this->tileToNum($game->end_tile);
        
        if( ($col < 0 || $col > $maxCol) || ($row <0 || $row > $maxRow) ) {
            return ['error' => 'tile out of bounds'];
        }
        
        $colTo = $col;
        $rowTo = $row;
        
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
        
        if( ($colTo < 0 || $colTo > $maxCol) || ($rowTo <0 || $rowTo > $maxRow) ) {
            return ['error' => 'tile out of bounds'];
        }
        
        $move = new Move;
        $move->user_id = Auth::user()->id;
        $move->game_id = $game->id;
        $move->action = 'put';
        $move->source_tile = $from;
        $move->ship_type = $type->id;
        $move->target_tile = $this->numToTile($colTo, $rowTo);
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
        if( !$this->sanity_check($id) ) return ['error' => 'failed sanity check'];
        $game = Game::where('public_id', '=', $id)->firstOrFail();
        
        // move out of bounds check to $game->outOfBounds(tile)
        
        list($col, $row) = $this->tileToNum($tile);
        list($maxCol, $maxRow) = $this->tileToNum($game->end_tile);
        
        if( ($col < 0 || $col > $maxCol) || ($row <0 || $row > $maxRow) ) {
            return ['error' => 'tile out of bounds'];
        }
        
        $move = new Move;
        $move->user_id = Auth::user()->id;
        $move->game_id = $game->id;
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
        if( !Auth::check() ) return ["error" => 'log in'];
        $game = Game::where('public_id', '=', $id)->firstOrFail();
        if(Auth::user()->id != $game->player1 && Auth::user()->id != $game->player2) return ["access denied"];
        if($game->player2 == null) return [false];
        
        if( $nomoves ) return [true];
        $moves = Move::where('game_id', '=', $game->id)
                     ->where('action', '=', '1');
        
        // verify that all ships have been placed
        return [true];
    }
}
