<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public $timestamps = false;
    protected $fillable = ['game_id', 'title', 'start_date', 'end_date', 'description'];

    public function game() {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }
}
