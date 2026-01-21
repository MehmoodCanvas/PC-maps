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
      If(Auth::guard('customer')->check()){
         return redirect(url('/dashboard'));
      }
      return view('front.signup');

   }
   public function login(){
      If(Auth::guard('customer')->check()){
         return redirect(url('/dashboard'));
      }
      return view('front.login');

   }

   public function checkout(){
      if(empty($_GET['id']) || !is_numeric($_GET['id'])  || !isset($_GET['id'])){
         return redirect(url('/dashboard'));
      }
      $maps = DB::table('map')->where('map_id',$_GET['id'])->orderBy('map_id',"DESC")->first();

      return view('front.checkout',compact('maps'));
      
   }
   public function dashboard(){
      $user = Auth::guard('customer')->user();
      $maps = DB::table('map')->where('map_customer_id', $user->customer_id)->orderBy('map_id',"DESC")->get();
      $orders = DB::table('order')->where('order_member_id', $user->customer_id)->join('map','order_map_id','map_id')->orderBy('order_id',"DESC")->get();
      return view('front.dashboard', compact('maps','orders', 'user'));
   }
   
  
}
