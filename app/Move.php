<?php

namespace ZeroGWars;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    /**
     * Indicates fields that should not be shown.
     *
     * @var bool
     */
    protected $hidden = array('game_id', 'updated_at', 'created_at');
}
