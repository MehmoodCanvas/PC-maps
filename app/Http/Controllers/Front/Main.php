<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

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
   
  
}
