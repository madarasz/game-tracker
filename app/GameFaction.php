<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameFaction extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'game_id', 'photo_id', 'big_photo_id', 'elo'];
    protected $appends = ['playerNumber', 'iconFile', 'factionFile'];
    protected $hidden = ['photo_id', 'big_photo_id'];


    public function game() {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }

    public function photo() {
        return $this->hasOne(Photo::class, 'id', 'photo_id');
    }

    public function big_photo() {
        return $this->hasOne(Photo::class, 'id', 'big_photo_id');
    }

    public function players() {
        return $this->hasMany(Player::class, 'faction_id', 'id');
    }

    public function getPlayerNumberAttribute() {
        return Player::where('faction_id', $this->id)->count();
    }

    public function getfactionFileAttribute() {
        if (!is_null($this->big_photo)) {
            return $this->big_photo->url;
        } 
        return null;
    }

    public function getIconFileAttribute() {
        if (!is_null($this->photo)) {
            return $this->photo->url;
        } 
        return null;
    }
}
