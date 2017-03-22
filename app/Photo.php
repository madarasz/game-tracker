<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'game_session_id', 'filename'];
    protected $hidden = ['game_session_id', 'filename'];
    protected $appends = ['url', 'thumbnail_url'];

    public function session() {
        return $this->hasOne(GameSession::class, 'id', 'game_session_id');
    }

    public function getUrlAttribute() {
        return '/img/photos/'.$this->filename;
    }

    public function getThumbnailUrlAttribute() {
        return '/img/photos/thumb_'.$this->filename;
    }
}
