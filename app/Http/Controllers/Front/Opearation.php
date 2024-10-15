<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Map;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Opearation extends Controller
{
    public function save(Request $request){
        $map = new Map();
        $data = $request->input('image'); 
        $width = $request->input('width'); 
        $height = $request->input('height'); 
        $text= $request->input('text');
        $compass= $request->input('compass');
        $addons= $request->input('addons');


        if($compass ===true){
             echo 4.99;
        }else{
            echo 0;

        }

        if($text <= 2){
            echo 0;
        }else{
            echo 4.99;
        }
        if($addons ===true){
            echo 9.99;
       }else{
           echo 0;

       }


        
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
        return response()->json(['message' => 'Image saved successfully', 'filename' => $total]);
    
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