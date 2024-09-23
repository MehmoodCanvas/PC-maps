<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
class Account extends Controller
{
    public function register(Request $request){
        $Customer= new Customer();
        $Customer->customer_first_name=$requst->customer_first_name; 
        $Customer->customer_last_name=$requst->customer_last_name;  
        $Customer->customer_email=$requst->customer_email;  
        $Customer->customer_password= Hash::make($requst->customer_password);  
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
}
