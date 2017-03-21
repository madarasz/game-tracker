<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['title', 'description', 'thumbnail_url', 'game_type_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'sessions'];
    protected $appends = ['sessionCount'];

    public function game_type() {
        return $this->hasOne(GameType::class, 'id', 'game_type_id');
    }

    public function sessions() {
        return $this->hasMany(GameSession::class, 'game_id', 'id');
    }

//    public function sessionCount() {
//        return $this->hasMany(GameSession::class)->selectRaw('game_id, count(*) AS count')->groupBy('game_id');
//    }
//
    public function getSessionCountAttribute() {
        return $this->sessions->count();
    }

}