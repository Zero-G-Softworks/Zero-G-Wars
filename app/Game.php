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
     * Show a list of all available flights.
     *
     * @return Response
     */
    public function index($id)
    {
        $game = Game::findOrFail($id);

        return $game;
    }
}
