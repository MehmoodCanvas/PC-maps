<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\{Map,Order,Setting};

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class Opearation extends Controller
{
    public function save(Request $request){
        try{
        $validatedData = $request->validate([
            'image' => 'required|string',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'text' => 'nullable',
            'compass' => 'nullable|boolean',
            'addons' => 'nullable|boolean',
        ]);

 

        $map = new Map();
        $data = $request->input('image'); 
        $width = $request->input('width'); 
        $height = $request->input('height'); 
        
        $text = $request->input('text') ? Setting::get('text_addon', 4.99) : 0;
        $compass = $request->input('compass') ? Setting::get('compass_addon', 4.99) : 0;
        $addons = $request->input('addons') ? Setting::get('addons_addon', 9.99) : 0;

        $width_multiplier = Setting::get('width_multiplier', 2);
        $dpi_multiplier = Setting::get('dpi_multiplier', 4);
        $scale_multiplier = Setting::get('scale_multiplier', 9.6);
        $base_addition = Setting::get('base_addition', 4.5);
        $base_price = Setting::get('base_price', 100);
        $base_multiplier = Setting::get('base_multiplier', 0.70);

        $frame_init = $height * $width * $width_multiplier;
        $frame_add = $base_addition + $frame_init * $dpi_multiplier;
        $frame_plus = $frame_add * $scale_multiplier;
        $frame_total = $frame_plus + $base_price;
        
        $total = $frame_total * $base_multiplier + $text + $compass + $addons;
    
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
        return response()->json(['message' => 'Image saved successfully', 'filename' => $total]);
        }catch (\Exception $e){
            return response()->json(['error' => 'Failed to save image', 'message' => $e->getMessage()], 500);
        }
       
    
       }

       public function createOrder(Request $request)
       {
        $map = DB::table('map')->where('map_id', $request['cart'][0]['id'])->first();
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
                    'reference_id' => (string) Str::uuid(), 
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $map->map_price,
                    ],
                ],
            ],
           ]);
   
           return response()->json($order->json());
       }
   
      
       public function captureOrder(Request $request, $orderID)
       {    
           $clientID = env('PAYPAL_CLIENT_ID');
           $secret = env('PAYPAL_SECRET');
       
           $tokenResponse = Http::withBasicAuth($clientID, $secret)
               ->asForm()
               ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                   'grant_type' => 'client_credentials',
               ]);
       
           if ($tokenResponse->failed()) {
               Log::error('Failed to obtain access token:', $tokenResponse->json());
               return response()->json(['error' => 'Failed to obtain access token'], 500);
           }
       
           $accessToken = $tokenResponse->json()['access_token'];
       
           $headers = [
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
            'PayPal-Request-Id' => Str::uuid()->toString(), 
        ];
        
        $captureResponse = Http::withHeaders($headers)
            ->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderID}/capture", [
                'amount' => [
                    'currency_code' => 'USD', 
                    'value' => $request->price, 
                ],
            ]);
            if ($captureResponse->failed()) {
               return response()->json(['error' => 'PayPal Capture Failed', 'details' => $captureResponse->json()], 400);
           }else{
                $map= Map::find($request->callid);
                $map->map_payment_status='Paid';
                $map->save();
                

                $order= new Order();
                $order->order_invoice_id=Str::random(5).'PC'.$request->callid;
                $order->order_address_one=$request->order_address_one;
                $order->order_address_two=$request->order_address_two;
                $order->order_zip_code=$request->order_zip_code;
                $order->order_payment_status='Paid';
                $order->order_status='Approved';
                $order->order_member_id=Auth::guard('customer')->user()->customer_id;
                $order->order_total_amount=$request->price;
                $order->order_map_id=$request->callid;
                $order->save();
           }
           return response()->json($captureResponse->json());
       }
    }