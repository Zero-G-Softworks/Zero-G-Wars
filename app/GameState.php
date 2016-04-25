<?php

namespace ZeroGWars;

use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Indicates fields that should not be shown.
     *
     * @var bool
     */
    protected $hidden = array('deleted_at', 'updated_at', 'created_at');
}
