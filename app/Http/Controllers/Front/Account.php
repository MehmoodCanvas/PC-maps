<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Account extends Controller
{
    public function register(Request $request){
        $Customer= new Customer();
        $Customer->customer_name=$request->customer_name; 
        $Customer->customer_email=$request->customer_email;  
        $Customer->customer_phone_number=$request->customer_phone_number;  
        $Customer->customer_password= Hash::make($request->customer_password);  
        $Customer->save(); 
        if($Customer->save()){
          $customerdata=Customer::find($Customer->customer_id);
             Auth::guard('customer')->login($customerdata);
             if(Auth::guard('customer')->check()){
                return redirect(url('/create-map'));
             }else{
                return redirect()->back()->with('error','Try again');
             }
       }; 
    }

    public function login(Request $requst){
      $customer=DB::table('customer')->where('customer_email',$request->customer_email)->first();
      if($customer){
          $password=Hash::check($request->customer_password, $customer->customer_password);
          if($password){
              Auth::guard('customer')->login($customerdata);
              if(Auth::guard('customer')->check()){
                  return redirect(url('/create-map'));

              }else{
               return redirect()->back()->with('error','Error in logging into the system');
              }
          }else{
               return redirect()->back()->with('error','Incorrect Password');
          }
      }else{
          return redirect()->back()->with('error','Wrong Email or Password');
      }
    }
}
