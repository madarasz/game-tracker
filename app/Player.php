<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    public $timestamps = false;
    protected $fillable = ['game_session_id', 'user_id', 'notes', 'score', 'winner'];
    protected $hidden = ['user_id', 'game_session_id'];

    public function session() {
        return $this->hasOne(GameSession::class, 'id', 'game_session_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
