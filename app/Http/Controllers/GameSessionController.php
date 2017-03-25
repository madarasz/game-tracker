<?php

namespace App\Http\Controllers;

use App\GameSession;
use App\Http\Requests\GameSessionRequest;
use App\Player;

class GameSessionController extends Controller
{
    public function cloneSession($sessionid) {
        $session = GameSession::findOrFail($sessionid);

        $newSession = $session->replicate();
        $newSession->concluded = false;
        $newSession->save();

        foreach($session->players as $player) {
            Player::create([
                'game_session_id' => $newSession->id,
                'user_id' => $player->user_id,
                'score' => 0
            ]);
        }

        return response()->json($newSession);
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

    public function indexForGame($gameid)
    {
        $sessions = GameSession::where('game_id', $gameid)
            ->with([
                'players' => function($q) {
                    $q->select('user_id', 'game_session_id', 'score', 'winner')->orderBy('score','desc');
                },
                'players.user'
            ])->orderBy('date', 'desc')->orderBy('id','desc')->get();

        return response()->json($sessions);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GameSessionRequest $request)
    {
        $created = GameSession::create($request->all());

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
        $session = GameSession::with(['game', 'players' => function($query) {
            $query->orderBy('score', 'desc');
        }, 'players.user', 'photos'])->findOrFail($id);
        $session->setHidden(['game_id', 'created_at', 'updated_at', 'deleted_at', 'photoCount']);

        return response()->json($session);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GameSessionRequest $request, $id)
    {
        $session = GameSession::findOrFail($id)->update($request->all());

        return response()->json($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        GameSession::find($id)->delete();

        return response()->json(['done']);
    }
}
