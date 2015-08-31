<?php

use ZeroGWars\User;
use ZeroGWars\Game;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->delete();
        
        Game::create([
            'public_id' => uniqid(),
            'player1' => User::where('username', '=', 'tuser')->first()->id,
            'player2' => User::where('username', '=', 'jdoe')->first()->id,
        ]);
    }
}
