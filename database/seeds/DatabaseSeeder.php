<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserSeeder::class);
        //$this->command->info('User table seeded!');
        $this->call(ShipTypeSeeder::class);
        $this->call(GameSeeder::class);

        Model::reguard();
    }
}
