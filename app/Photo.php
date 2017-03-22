<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'game_session_id'];

    public function session() {
        return $this->hasOne(GameSession::class, 'id', 'game_session_id');
    }
}
