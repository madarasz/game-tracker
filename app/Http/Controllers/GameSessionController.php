<?php

namespace App\Http\Controllers;

use App\GameSession;
use App\Http\Requests\GameSessionRequest;
use App\Player;
use App\Season;

class GameSessionController extends Controller
{
    public function cloneSession($sessionid) {
        $session = GameSession::findOrFail($sessionid);

        $newSession = $session->replicate();
        $this->setSeason($newSession);
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

    public function indexForGame($gameid, $seasonid = null)
    {
        $sessions = GameSession::where('game_id', $gameid);

        if ($seasonid != null) {
            if (intval($seasonid) > 0) {
                $sessions = $sessions->where('season_id', $seasonid);
            } else {
                $sessions = $sessions->whereNull('season_id');
            }
        }

        $sessions = $sessions->with([
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

        // set season
        $this->setSeason($created);

        return $this->show($created->id);
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
        }, 'players.user', 'photos', 'season'])->findOrFail($id);
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
        $session = GameSession::findOrFail($id);
        $session->update($request->all());

        // set season
        $this->setSeason($session);

        return $this->show($session->id);
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

    private function setSeason(&$session) {
        $season = Season::where('game_id', $session->game_id)
            ->where('start_date', '<=', $session->date)->where('end_date', '>=', $session->date)->first();
        if ($season) {
            $session->update(['season_id' => $season->id]);
        } else {
            $session->update(['season_id' => null]);
        }
    }
}
