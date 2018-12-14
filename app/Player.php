<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    public $timestamps = false;
    protected $fillable = ['game_session_id', 'user_id', 'notes', 'score', 'winner', 'faction_id'];
    protected $hidden = ['user_id', 'game_session_id'];
    protected $appends = ['eloDelta'];

    public function session() {
        return $this->hasOne(GameSession::class, 'id', 'game_session_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function faction() {
        return $this->hasOne(GameFaction::class, 'id', 'faction_id');
    }

    public function elo_score($game_id, $season_id) {
        return EloPoint::where('user_id', $this->user_id)->where('season_id', $season_id)->where('game_id', $game_id)->first();
    }

    public function elo_delta() {
        return EloDelta::where('user_id', $this->user_id)->where('game_session_id', $this->game_session_id)->first();
    }

    public function getEloDeltaAttribute() {
        if ($this->elo_delta()) {
            return $this->elo_delta()->delta;
        } else {
            return 0;
        }
    }

}
