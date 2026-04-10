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
            'frame_style' => 'nullable|string',
        ]);

 

        $map = new Map();
        $data = $request->input('image'); 
        $width = $request->input('width'); 
        $height = $request->input('height'); 
        $frameStyle = $request->input('frame_style', 'none');
        
        $text = $request->input('text') ? Setting::get('text_addon', 4.99) : 0;
        $compass = $request->input('compass') ? Setting::get('compass_addon', 4.99) : 0;
        $addons = $request->input('addons') ? Setting::get('addons_addon', 9.99) : 0;

        $map_multiplier = Setting::get('map_multiplier', 0.8);
        $mapBaseCost = ($width * $height) * $map_multiplier;
        
        $frameCost = 0;
        if ($frameStyle !== 'none') {
            $perimeter = 2 * ($width + $height);
            $frameCostPerInch = Setting::get('frame_cost_per_inch', 2.50);
            $frameMultiplier = Setting::get('frame_multiplier_' . str_replace(' ', '_', $frameStyle), 1.2);
            $frameCost = $perimeter * $frameCostPerInch * $frameMultiplier;
        }

        $total = $mapBaseCost + $text + $compass + $addons + $frameCost;
    
        $image = str_replace('data:image/png;base64,', '', $data);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        $map->map_unqiue_id=Str::uuid();
        $map->map_data=$data;
        $map->map_width=$width;
        $map->map_height=$height;
        $map->map_price=$total;
        $map->map_base_cost=$mapBaseCost;
        $map->map_addon_cost=$text + $compass + $addons;
        $map->map_frame=$frameStyle;
        $filename = Str::uuid() . time() . '.png';
        $map->map_image=$filename;
        $map->map_customer_id = Auth::guard('customer')->user()->customer_id;
        $map->save();

        if (!Storage::disk('public')->exists('images/maps')) {
            Storage::disk('public')->makeDirectory('images/maps');
        }

        $saved = Storage::disk('public')->put('images/maps/' . $filename, $imageData);
        
        if (!$saved) {
            Log::error("Failed to save map image to disk for map ID: " . $map->map_id);
            return response()->json(['error' => 'Failed to save image file to disk'], 500);
        }

        return response()->json([
            'message' => 'Image saved successfully', 
            'price' => number_format($total, 2),
            'map_id' => $map->map_id,
            'map_cost' => number_format($mapBaseCost, 2),
            'frame_cost' => number_format($frameCost, 2),
            'frame_style' => $frameStyle,
        ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save image', 'message' => $e->getMessage()], 500);
        }
       
    
       }

       public function updateFrame(Request $request) {
           try {
               $validatedData = $request->validate([
                   'map_id' => 'required|numeric',
                   'frame_style' => 'nullable|string',
               ]);

               $map = Map::find($request->map_id);
               if (!$map) {
                   return response()->json(['error' => 'Map not found'], 404);
               }

               $frameStyle = $request->input('frame_style', 'none');
               $width = $map->map_width;
               $height = $map->map_height;

               $frameCost = 0;
               if ($frameStyle !== 'none') {
                   $perimeter = 2 * ($width + $height);
                   $frameCostPerInch = Setting::get('frame_cost_per_inch', 2.50);
                   $frameMultiplier = Setting::get('frame_multiplier_' . str_replace(' ', '_', $frameStyle), 1.2);
                   $frameCost = $perimeter * $frameCostPerInch * $frameMultiplier;
               }

               $map->map_price = $map->map_base_cost + $map->map_addon_cost + $frameCost;
               $map->map_frame = $frameStyle;
               $map->save();

               return response()->json([
                   'message' => 'Frame updated successfully',
                   'new_price' => number_format($map->map_price, 2),
                   'frame_cost' => number_format($frameCost, 2),
                   'map_id' => $map->map_id,
                   'frame_style' => $frameStyle
               ]);

           } catch (\Exception $e) {
               return response()->json(['error' => 'Failed to update frame', 'message' => $e->getMessage()], 500);
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