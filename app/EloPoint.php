<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EloPoint extends Model
{
    public $timestamps = false;
    protected $fillable = ['game_id', 'user_id', 'points', 'season_id'];
    protected $hidden = ['id', 'game_id', 'user'];
    protected $appends = ['userName'];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserNameAttribute() {
        return $this->user->name;
    }

    public function season() {
        return $this->hasOne(Season::class, 'id', 'season_id');
    }
}
