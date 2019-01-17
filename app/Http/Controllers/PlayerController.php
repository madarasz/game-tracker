<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Player;
use App\User;
use App\GameSession;
use App\Game;

class PlayerController extends Controller
{
    public function indexUsers() {
        $users = User::get();
        return response()->json($users);
    }

    public function userDetail($userId) {
        $user = User::findOrFail($userId);
        $sessionIds = Player::where('user_id', $userId)->pluck('game_session_id')->toArray();
        $gameIds = GameSession::whereIn('id', $sessionIds)->groupBy('game_id')->pluck('game_id')->toArray();
        $games = Game::whereIn('id', $gameIds)->with(['seasons', 'seasons.points' => function($q) {
            $q->orderBy('points', 'desc');
        }])->get();
        return response()->json(['user' => $user, 'games' => $games]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function indexForSession($sessionid) {
        $players = Player::where('game_session_id', $sessionid)->with('user')->get();

        return response()->json($players);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PlayerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlayerRequest $request)
    {
        $created = Player::create($request->all());

        $this->updateWinnerForSessoin($created);

        return response()->json($created);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PlayerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlayerRequest $request, $id)
    {
        $player = Player::findOrFail($id);
        $player->update($request->all());

        $this->updateWinnerForSessoin($player);

        return response()->json($player);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Player::find($id)->delete();

        return response()->json(['done']);
    }

    public function updateWinnerForSessoin($player) {
        $top_score = Player::where('game_session_id', $player->game_session_id)->max('score');
        Player::where('game_session_id', $player->game_session_id)->update(['winner' => false]);
        Player::where('game_session_id', $player->game_session_id)->where('score', $top_score)->update(['winner' => true]);
    }
}
