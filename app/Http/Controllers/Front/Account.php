<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    public function login(Request $request){
      $customer=DB::table('customer')->where('customer_email',$request->customer_email)->first();
      if($customer){
          $password=Hash::check($request->customer_password, $customer->customer_password);
          if($password){
            $customerdata=Customer::find($customer->customer_id);
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

    public function updateProfile(Request $request){
      $user = Auth::guard('customer')->user();
      
      $request->validate([
          'customer_name' => 'required|string|max:255',
          'customer_email' => 'required|email',
          'customer_phone_number' => 'nullable|string|max:20',
      ]);

      $customer = Customer::find($user->customer_id);
      $customer->customer_name = $request->customer_name;
      $customer->customer_email = $request->customer_email;
      $customer->customer_phone_number = $request->customer_phone_number;
      $customer->save();

      return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function uploadProfilePicture(Request $request){
      $request->validate([
          'customer_profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
      ]);

      $user = Auth::guard('customer')->user();
      $customer = Customer::find($user->customer_id);

      // Delete old profile picture if exists
      if($customer->customer_profile_picture && Storage::exists('public/' . $customer->customer_profile_picture)) {
          Storage::delete('public/' . $customer->customer_profile_picture);
      }

      // Store new profile picture
      if($request->hasFile('customer_profile_picture')) {
          $file = $request->file('customer_profile_picture');
          $fileName = 'profile_' . $user->customer_id . '_' . time() . '.' . $file->getClientOriginalExtension();
          $path = $file->storeAs('profile-pictures', $fileName, 'public');
          
          $customer->customer_profile_picture = $path;
          $customer->save();

          return redirect()->back()->with('success', 'Profile picture updated successfully!');
      }

      return redirect()->back()->with('error', 'Failed to upload profile picture');
    }
}
