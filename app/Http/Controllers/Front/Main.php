<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Main extends Controller
{
   public function index(){
    
      return view('front.index');

   }
   public function signup(){
    
      return view('front.signup');

   }
   public function login(){
    
      return view('front.login');

   }

   public function checkout(){
      $maps = DB::table('map')->where('map_id',$_GET['id'])->orderBy('map_id',"DESC")->first();

      return view('front.checkout',compact('maps'));
      
   }
   public function dashboard(){
      $maps = DB::table('map')->where('map_customer_id',Auth::guard('customer')->user()->customer_id)->orderBy('map_id',"DESC")->get();
      $orders= DB::table('order')->where('order_member_id',Auth::guard('customer')->user()->customer_id)->join('map','order_map_id','map_id')->orderBy('order_id',"DESC")->get();
      return view('front.dashboard',compact('maps','orders'));

   }
   
  
}
