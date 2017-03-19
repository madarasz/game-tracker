<?php

use Illuminate\Database\Seeder;

class GameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->insert([
            [
                'id' => 1,
                'title' => 'Agricola',
                'description' => 'Best game. Ever.',
                'thumbnail_url' => '//cf.geekdo-images.com/images/pic259085.jpg',
                'game_type_id' => 1
            ],
            [
                'id' => 2,
                'title' => 'Duck Game',
                'description' => 'Mhmm... Ducks',
                'thumbnail_url' => 'http://cdn.akamai.steamstatic.com/steam/apps/312530/header.jpg?t=1461797380',
                'game_type_id' => 2
            ]
        ]);
    }
}
