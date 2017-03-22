<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Photo;

class PhotoController extends Controller
{

    public function indexForSession($sessionid) {
        $photos = Photo::where('game_session_id', $sessionid)->get();

        return response()->json($photos);
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
     * @param  \App\Http\Requests\PhotoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PhotoRequest $request)
    {
        // validating successful upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // adding to the DB
            $created = Photo::create($request->all());
            // saving photo
            $request->photo->storeAs('img/photos', $created->id.'.jpg');

            return response()->json($created);
        }
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
     * @param  \App\Http\Requests\PhotoRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PhotoRequest $request, $id)
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
        //
    }
}
