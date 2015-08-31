<?php

use ZeroGWars\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        
        User::create([
            'name' => "Todd User",
            'email' => "tuser@test.com",
            'username' => "tuser",
            'password' => bcrypt("123"),
        ]);
        
        User::create([
            'name' => "Jane Doe",
            'email' => "jdoe@test.com",
            'username' => "jdoe",
            'password' => bcrypt("123"),
        ]);
    }
}
