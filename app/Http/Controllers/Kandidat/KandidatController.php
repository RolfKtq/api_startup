<?php

namespace App\Http\Controllers\Kandidat;

use App\CV_post;
use App\Http\Controllers\Controller;
use App\Image;
use App\Kandidat;
use Illuminate\Http\Request;

class KandidatController extends Controller
{

    public function index(Request $request)
    {
        $kandidater = Kandidat::with('cv_poster')->get();
        return response()->json(['data' => $kandidater], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'fornavn' => 'required',
        ];

        $this->validate($request, $rules);
        $new = Kandidat::create($request->all());
        return response()->json(['data' => $new], 200);
    }

    public function show(Kandidat $kandidat, $id, Request $request)
    {
        $kandidat = Kandidat::findOrFail($id);

        $kandidat->load('cv_poster');
        return response()->json(['data' => $kandidat], 200);
    }

    public function update(Request $request, $id)
    {
        $kandidat = Kandidat::findOrFail($id);

        $kandidat->fill($request->only([
            'fornavn',
            'etternavn',
            'adresse',
            'postnummer',
            'poststed',
            'fodselsaar',
            'mail',
            'telefon',
            'image_id',
            'nasjonalitet',
            'flyplass',
            'primaerkompetanse',
            'dawinci',
            'teskst',
        ]));

        if ($request->has('fornavn')) {$kandidat->fornavn = $request->fornavn;}
        if ($request->has('etternavn')) {$kandidat->etternavn = $request->etternavn;}
        if ($request->has('adresse')) {$kandidat->adresse = $request->adresse;}
        if ($request->has('postnummer')) {$kandidat->postnummer = $request->postnummer;}

        if ($request->has('poststed')) {$kandidat->poststed = $request->poststed;}
        if ($request->has('fodselsaar')) {$kandidat->fodselsaar = $request->fodselsaar;}
        if ($request->has('mail')) {$kandidat->mail = $request->mail;}
        if ($request->has('telefon')) {$kandidat->telefon = $request->telefon;}
     
        if ($request->has('image_id')) {
            if ($request->image_id == 'slett') {
                $kandidat->image_id = $request->null;
            } else {
                $kandidat->image_id = $request->image_id;
            }

        }

        if ($request->has('nasjonalitet')) {$kandidat->nasjonalitet = $request->nasjonalitet;}
        if ($request->has('flyplass')) {$kandidat->flyplass = $request->flyplass;}
        if ($request->has('primaerkompetanse')) {$kandidat->primaerkompetanse = $request->primaerkompetanse;}
        if ($request->has('dawinci')) {$kandidat->dawinci = $request->dawinci;}
        if ($request->has('tekst')) {$kandidat->tekst = $request->tekst;}


        if ($kandidat->isClean()) {
        }

        if ($kandidat->image_id == 'ny') {
            $kandidat->image_id = null;
        }

        $kandidat->save();
        $kandidat->load('cv_poster');
        return response()->json(['data' => $kandidat], 200);
    }

    public function destroy($id, Request $request)
    {
        $kandidat = Kandidat::findOrFail($id);
        $kandidat->load('cv_poster');

        foreach ($kandidat->cv_poster as $cv_post) {

            $post = CV_post::findOrFail($cv_post->id);
            $post->delete();

        }

        if ($kandidat->image_id != null) {
            $image = Image::findOrFail($kandidat->image_id);
            $image->delete();
        }
        $kandidat->delete();
        return response()->json(['data' => $kandidat], 200);
    }
}
