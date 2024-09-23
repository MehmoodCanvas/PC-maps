<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Map;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

class Opearation extends Controller
{
    public function save(Request $request){
        $map = new Map();
        $data = $request->input('image'); 
        $width = $request->input('width'); 
        $height = $request->input('height'); 
    
        $total = $height * $width * '.70';
    
        $image = str_replace('data:image/png;base64,', '', $data);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        $map->map_unqiue_id=Str::uuid();
        $map->map_data=$data;
        $map->map_width=$width;
        $map->map_height=$height;
        $map->map_price=$total;
        $filename = Str::uuid() . time() . '.png';
        $map->map_image=$filename;
        $map->map_customer_id=Auth::guard('customer')->user()->customer_id;
        $map->save();
        Storage::disk('public')->put('images/maps/' . $filename, $imageData);
        // Image::load(storage_path('app/public/images/' . $filename))
        // ->watermark('preview')
        // ->save(storage_path('app/public/images/modified-' . $filename));
        return response()->json(['message' => 'Image saved successfully', 'filename' => $total]);
    
       }
}
