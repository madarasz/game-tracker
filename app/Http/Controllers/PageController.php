<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function marsRandomizer() {
        return view('random-mars');
    }

    public function userDetails($userId) {
        return view('user-details', ['userId' => $userId]);
    }
}
