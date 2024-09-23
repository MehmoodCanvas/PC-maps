<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Main extends Controller
{
   public function index(){
    return view ('front.index');
   }

   public function save(Request $request){
    $data = $request->input('image'); 
    $width = $request->input('width'); 
    $height = $request->input('height'); 
    $image = str_replace('data:image/png;base64,', '', $data);
    $image = str_replace(' ', '+', $image);
    $imageData = base64_decode($image);
    $filename = 'overlay_snapshot_' . time() . '.png';
    Storage::disk('public')->put('images/' . $filename, $imageData);
    return response()->json(['message' => 'Image saved successfully', 'filename' => $filename]);

   }
}
