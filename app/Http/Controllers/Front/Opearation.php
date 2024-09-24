<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Map;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Illuminate\Support\Facades\Http;

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

       public function createOrder(Request $request)
       {
           $clientID = env('PAYPAL_CLIENT_ID');
           $secret = env('PAYPAL_SECRET');
           
           $response = Http::withBasicAuth($clientID, $secret)
               ->asForm()
               ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                   'grant_type' => 'client_credentials',
               ]);
   
           $accessToken = $response->json()['access_token'];
   
           $order = Http::withToken($accessToken)
               ->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
                   'intent' => 'CAPTURE',
                   'purchase_units' => [
                       [
                           'amount' => [
                               'currency_code' => 'USD',
                               'value' => '100.00',  
                           ],
                       ],
                   ],
               ]);
   
           return response()->json($order->json());
       }
   
       public function captureOrder($orderID)
       {
           $clientID = env('PAYPAL_CLIENT_ID');
           $secret = env('PAYPAL_SECRET');
           
           $response = Http::withBasicAuth($clientID, $secret)
               ->asForm()
               ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                   'grant_type' => 'client_credentials',
               ]);
   
           $accessToken = $response->json()['access_token'];
   
           $capture = Http::withToken($accessToken)
               ->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderID}/capture");
   
           return response()->json($capture->json());
       }

    }