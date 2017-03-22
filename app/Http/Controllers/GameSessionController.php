<?php

namespace App\Http\Controllers;

use App\GameSession;
use App\Http\Requests\GameSessionRequest;

class GameSessionController extends Controller
{
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
        $sessions = GameSession::where('game_id', $gameid)->get();

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
        }, 'players.user'])->findOrFail($id);
        $session->setHidden(['game_id', 'created_at', 'updated_at', 'deleted_at']);

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
        $session = GameSession::find($id)->update($request->all());

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
