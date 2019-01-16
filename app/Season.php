<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public $timestamps = false;
    protected $fillable = ['game_id', 'title', 'start_date', 'end_date', 'description'];
    protected $appends = ['sessionCount'];

    public function game() {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }

    public function points() {
        return $this->hasMany(EloPoint::class, 'season_id', 'id');
    }

    public function getSessionCountAttribute() {
        return GameSession::where('game_id', $this->game_id)->where('season_id', $this->id)->count();
    }
}
