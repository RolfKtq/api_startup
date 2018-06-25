<?php
namespace App\Http\Controllers\Image;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Image;
use App\Kandidat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller{
  public function __construct(){
  //  $this->middleware('auth:api');
  }
   
  public function index(Request $request){
    $images=Image::all();
    return response()->json(['data'=>$images],200);
  }

  public function store(Request $request){
    $rules = [
    // 'navn' => 'required',
    ];

    $this->validate($request, $rules);    
    $new = Image::create($request->all());
    return  response()->json(['data'=>$new],200);
  }
  
  public function show(Image $image, $id, Request $request){
    $image=Image::findOrFail($id);
    return response()->json(['data'=>$image],200);
  }
   
  public function update(Request $request,$id){
    $image=image::findOrFail($id);
    $image->fill($request->only([
      'image',
      'width',
      'height',
    ]));
      
    if ($image->isClean()) {
     // return   $this->errorResponse('Du har ikke endret noe.', 422);
    }
    $image->save();
    return response()->json(['data'=>$image],200);
  }
  
  public function destroy($id, Request $request){
    $image=image::findOrFail($id);
    $image->delete();
    return response()->json(['data' => $image],200); 
  }  
  
  
  public function image($fileName,  Request $request){
    $path = public_path().'/uploads/images/'.$fileName;
    return Response::download($path);        
  }
}