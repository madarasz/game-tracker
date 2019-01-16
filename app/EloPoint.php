<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EloPoint extends Model
{
    public $timestamps = false;
    protected $fillable = ['game_id', 'user_id', 'points', 'season_id'];
    protected $hidden = ['id', 'game_id', 'user'];
    protected $appends = ['userName', 'sessionCount'];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserNameAttribute() {
        return $this->user->name;
    }

    public function season() {
        return $this->hasOne(Season::class, 'id', 'season_id');
    }

    public function getSessionCountAttribute() {
        $sessionIds = GameSession::where('season_id', $this->season_id)->where('game_id', $this->game_id)->pluck('id');
        return Player::whereIn('game_session_id', $sessionIds)->where('user_id', $this->user_id)->count();
    }
}
