<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    public function login()
    {
        if(Auth::guard('admin')->check()) {
            return redirect(url('/admin/dashboard'));
        }
        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:6'
        ]);

        $admin = DB::table('admin_users')->where('admin_email', $request->admin_email)->first();

        if($admin && Hash::check($request->admin_password, $admin->admin_password)) {
            if(!$admin->is_active) {
                return redirect()->back()->with('error', 'Admin account is inactive');
            }

            $adminData = AdminUser::find($admin->admin_id);
            Auth::guard('admin')->login($adminData);
            
            $adminData->update(['last_login' => now()]);

            return redirect(url('/admin/dashboard'));
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect(url('/admin/login'));
    }
}
