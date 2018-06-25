<?php

namespace App\Http\Controllers\CV_post;

use App\CV_post;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Request;

class CV_postController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $cv_poster = CV_post::all();
        return response()->json(['data' => $cv_poster], 200);
    }

    public function store(Request $request)
    {
        $rules = [
        ];
        $this->validate($request, $rules);

        $new = CV_post::create($request->all());
        return response()->json(['data' => $new], 200);
    }

    public function show(Rigutleie $cv_post, $id, Request $request)
    {
        $cv_post = CV_post::findOrFail($id);
        return response()->json(['data' => $cv_post], 200);
    }

    public function update(Request $request, $id)
    {
        $cv_post = CV_post::findOrFail($id);
        $cv_post->fill($request->only([
            'fra_dato',
            'til_dato',
            'kandidat_id',
            'image_id',
            'type_post',
            'tittel',
            'beskrivelse',
            'arrangor',
            'arbeidsgiver',
            'utskrift',
        ]));

        if ($request->has('fra_dato')) {$cv_post->fra_dato = $request->fra_dato;}
        if ($request->has('til_dato')) {$cv_post->til_dato = $request->til_dato;}
        if ($request->has('kandidat_id')) {$cv_post->kandidat_id = $request->kandidat_id;}
        //    if($request->has('image_id')){$cv_post->image_id=$request->image_id;}

        if ($request->has('image_id')) {
            if ($request->image_id == 'slett') {
                //    print 'SETTER image_id TIL NULL PÅ STOFFER med id '.$id;
                $cv_post->image_id = $request->null;

            } else {
                $cv_post->image_id = $request->image_id;
            }

        }

        if ($request->has('type_post')) {$cv_post->type_post = $request->type_post;}
        if ($request->has('tittel')) {$cv_post->tittel = $request->tittel;}
        if ($request->has('beskrivelse')) {$cv_post->beskrivelse = $request->beskrivelse;}
        if ($request->has('arrangor')) {$cv_post->arrangor = $request->arrangor;}
        if ($request->has('arbeidsgiver')) {$cv_post->arbeidsgiver = $request->arbeidsgiver;}
        if ($request->has('utskrift')) {$cv_post->utskrift = $request->utskrift;}

        if ($cv_post->isClean()) {
            //    return   $this->errorResponse('Du har ikke endret noe.', 422);
        }

        if ($cv_post->image_id == 'ny') {
            $cv_post->image_id = null;
        }
        $cv_post->save();
        return response()->json(['data' => $cv_post], 200);
    }

    public function destroy($id, Request $request)
    {
        $cv_post = CV_post::findOrFail($id);

        if ($cv_post->image_id) {
            $image = Image::findOrFail($cv_post->image_id);
            $image->delete();
        }
        $cv_post->delete();
        return response()->json(['data' => $cv_post], 200);
    }

}
