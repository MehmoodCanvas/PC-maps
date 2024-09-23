<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

class Main extends Controller
{
   public function index(){
    return view ('front.index');
   }

   public function save(Request $request){
    $data = $request->input('image'); 
    $width = $request->input('width'); 
    $height = $request->input('height'); 

    $total = $height * $width * '.70';

    $image = str_replace('data:image/png;base64,', '', $data);
    $image = str_replace(' ', '+', $image);
    $imageData = base64_decode($image);
    $filename = 'overlay_snapshot_' . time() . '.png';
    Storage::disk('public')->put('images/' . $filename, $imageData);
    // Image::load(storage_path('app/public/images/' . $filename))
    // ->watermark('preview')
    // ->save(storage_path('app/public/images/modified-' . $filename));
    return response()->json(['message' => 'Image saved successfully', 'filename' => $total]);

   }
}
