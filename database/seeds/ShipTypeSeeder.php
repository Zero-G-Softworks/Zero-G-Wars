<?php

use ZeroGWars\ShipType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ShipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ship_types')->delete();
        
        ShipType::create([
            'name' => "Frigate",
            'length' => 2,
        ]);
        
        ShipType::create([
            'name' => "Destroyer",
            'length' => 3,
        ]);
        
        ShipType::create([
            'name' => "Cruiser",
            'length' => 3,
        ]);
        
        ShipType::create([
            'name' => "Battleship",
            'length' => 4,
        ]);
        
        ShipType::create([
            'name' => "Capital Ship",
            'length' => 5,
        ]);
    }
}
