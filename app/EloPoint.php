<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EloPoint extends Model
{
    public $timestamps = false;
    protected $fillable = ['game_id', 'user_id', 'points'];
    protected $hidden = ['id', 'game_id', 'user'];
    protected $appends = ['userName'];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserNameAttribute() {
        return $this->user->name;
    }
}
