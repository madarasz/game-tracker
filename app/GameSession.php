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
    protected $appends = ['photoCount'];

    public function game() {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }

    public function players() {
        return $this->hasMany(Player::class, 'game_session_id');
    }

    public function photos() {
        return $this->hasMany(Photo::class, 'game_session_id');
    }

    public function getPhotoCountAttribute() {
        return $this->photos->count();
    }
}
