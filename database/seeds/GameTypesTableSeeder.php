<?php

use Illuminate\Database\Seeder;

class GameTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('game_types')->insert([
            ['id' => 1, 'type' => 'boardgame'],
            ['id' => 2, 'type' => 'videogame']
        ]);
    }
}
