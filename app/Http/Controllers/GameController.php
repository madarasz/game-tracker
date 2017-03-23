<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Requests\GameRequest;

class GameController extends Controller
{

    public function manageGames() {
        return view('games');
    }

    public function viewGame($id) {
        return view('sessions', ['id' => $id]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::get();

        return response()->json($games);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // NOT USED
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GameRequest $request)
    {
        $created = Game::create($request->all());

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
        $game = Game::findOrFail($id);

        return response()->json($game);
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
     * @param  \App\Http\Requests\GameRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GameRequest $request, $id)
    {
        $edit = Game::findOrFail($id)->update($request->all());

        return response()->json($edit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Game::find($id)->delete();
        return response()->json(['done']);
    }
}
