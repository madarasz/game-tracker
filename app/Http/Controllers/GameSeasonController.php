<?php

namespace App\Http\Controllers;

use App\GameSession;
use App\Http\Requests\GameSeasonRequest;
use App\Season;
use Illuminate\Http\Request;

class GameSeasonController extends Controller
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
    public function store(GameSeasonRequest $request)
    {
        // check for overlapping seasons
        $allSeasons = Season::where('game_id', $request->input('game_id'))->get();
        foreach ($allSeasons as $season) {
            if (($request->input('start_date') >= $season->start_date) &&
                ($request->input('start_date') <= $season->end_date) ||
                ($request->input('end_date') >= $season->start_date) &&
                ($request->input('end_date') <= $season->end_date)) {
                return response('{"start_date":["The dates overlap with an existing season."],"end_date":["The dates overlap with an existing season."]}', 422);
            }
        }

        // create season
        $created = Season::create($request->all());

        // assign sessions to season
        GameSession::where('date', '>=', $created->start_date)->where('date', '<=', $created->end_date)->
            update(['season_id' => $created->id]);

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $season = Season::find($id);

        // reset sessions
        GameSession::where('season_id', $season->id)->update(['season_id' => null]);

        // delete season
        $season->delete();

        return response()->json(['done']);
    }
}
