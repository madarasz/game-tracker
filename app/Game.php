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
    protected $appends = ['sessionCount', 'leader', 'activeSeason', 'sessionsWithoutSeason'];

    public function game_type() {
        return $this->hasOne(GameType::class, 'id', 'game_type_id');
    }

    public function sessions() {
        return $this->hasMany(GameSession::class, 'game_id', 'id');
    }

    public function getSessionCountAttribute() {
        return $this->sessions->count();
    }

    public function elo_ranking() {
        return $this->hasMany(EloPoint::class, 'game_id', 'id')->orderBy('points', 'desc');
    }

    public function getLeaderAttribute() {
        return $this->elo_ranking()->first();
    }

    public function seasons() {
        return $this->hasMany(Season::class, 'game_id', 'id')->orderBy('end_date', 'desc');
    }

    public function getActiveSeasonAttribute() {
        $today = date('Y-m-d');
        return $this->seasons()->where('start_date', '<=', $today)->where('end_date', ">=", $today)->first();
    }

    public function getSessionsWithoutSeasonAttribute() {
        return $this->sessions()->whereNull('season_id')->count();
    }

}