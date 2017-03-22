<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Photo;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

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

            // saving photo and thumbnail
            $filename = $created->id.'.'.$request->photo->extension();
            $request->photo->storeAs('public/img/photos', $filename);
            $request->photo->storeAs('public/img/photos', 'thumb_' . $filename);

            // resizing image
            $img = Image::make(storage_path('app/public/img/photos/') . $filename);
            $img->resize(1024, 1024, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save();

            // trimming to square
            $thumb = Image::make(storage_path('app/public/img/photos/thumb_') . $filename);
            $dim = min($thumb->height(), $thumb->width());
            $thumb->resizeCanvas($dim, $dim, 'center');
            // resize
            $thumb->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $thumb->save();

            // move to public
            File::move(storage_path('app/public/img/photos/'.$filename), public_path('img/photos/'.$filename));
            File::move(storage_path('app/public/img/photos/thumb_'.$filename), public_path('img/photos/thumb_'.$filename));

            // saving filename in DB
            Photo::findOrFail($created->id)->update(['filename' => $filename]);

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
        Photo::find($id)->delete();

        return response()->json(['done']);
    }
}
