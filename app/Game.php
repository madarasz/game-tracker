<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['title', 'description', 'url', 'thumbnail_url', 'game_type_id', 'foreign_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function game_type() {
        return $this->hasOne(GameType::class, 'id', 'game_type_id');
    }

}