<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EloDelta extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'game_session_id', 'delta'];
}
