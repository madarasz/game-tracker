<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameSession extends Model
{

    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['date', 'place', 'notes', 'game_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function game() {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'game_session_user', 'game_session_id', 'user_id');
    }
}