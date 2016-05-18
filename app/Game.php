<?php

namespace ZeroGWars;

use Illuminate\Database\Eloquent\Model;
use ZeroGWars\GameState;

class Game extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "games";

    /**
     * Indicates fields that should not be shown.
     *
     * @var bool
     */
    protected $hidden = array('deleted_at', 'updated_at', 'created_at');

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The ending tile used to indicated board size.
     * 
     * This value may be moved to the database in the future to support variable
     * game board sizes.
     * 
     * @var string
     */
    private $end_tile = "O15";

    /**
     * Find a particular game
     *
     * @return Response
     */
    public function index($id)
    {
        $game = Game::findOrFail($id);

        return $game;
    }

    /**
     * Get the last tile of the game board as a tile coordinate.
     *
     * @return tile
     */
    public function getEndTile()
    {
        return $this->end_tile;
    }
}
