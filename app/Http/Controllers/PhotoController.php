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
            $img->resize(2048, 2048, function ($constraint) {
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
     * Rotates image 90 degrees clockwise or counter-clockwise.
     * @param $request
     * @param $id
     * @param $dir 'cw' or 'ccw' for clockwise or counter-clockwise
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rotate(PhotoRequest $request, $id, $dir) {
        $photo = Photo::findOrFail($id);

        // new file name: image.jpg > rot1_image.jpg > rot2_image.jpg > rot3_image.jpg > image.jpg
        if (preg_match('/rot(\d)/', $photo->filename, $matches)) {
            $rotnum = intval($matches[1]);
            $filename = substr($photo->filename, 5);
        } else {
            $rotnum = 0;
            $filename = $photo->filename;
        }
        // calculate rotation, new rotation number
        $degrees = 0;
        if ($dir == 'cw') {
            $rotnum++;
            $degrees = -90;
        } elseif ($dir === 'ccw') {
            $rotnum--;
            if ($rotnum < 0) {
                $rotnum = $rotnum + 4;
            }
            $degrees = 90;
        }
        $rotnum = $rotnum % 4;
        // calculate new file name
        if ($rotnum) {
            $newFilename = 'rot'.$rotnum.'_'.$filename;
        } else {
            $newFilename = $filename;
        }

        // rotate image
        $img = Image::make(public_path('img/photos/'.$photo->filename));
        $img->rotate($degrees);
        $img->save();
        // rotate thumbnail
        $thumb = Image::make(public_path('img/photos/thumb_'.$photo->filename));
        $thumb->rotate($degrees);
        $thumb->save();

        // rename image
        rename(public_path('img/photos/'.$photo->filename), public_path('img/photos/'.$newFilename));
        // rename thumbnail
        rename(public_path('img/photos/thumb_'.$photo->filename), public_path('img/photos/thumb_'.$newFilename));
        // update DB
        $photo->update(['filename' => $newFilename]);

        // redirecting to tournament
        return response()->json('Photo rotated');
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
