<?php

namespace ZeroGWars;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "games";

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
    public $end_tile = "O15";

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
}
