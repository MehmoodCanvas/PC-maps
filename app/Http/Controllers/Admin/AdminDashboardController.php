<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $totalCustomers = DB::table('customer')->count();
        $totalOrders = DB::table('order')->count();
        $totalRevenue = DB::table('order')->sum('order_total_amount');
        $pendingOrders = DB::table('order')->where('order_status', 'Pending')->count();
        
        $recentOrders = DB::table('order')
            ->join('customer', 'order_member_id', 'customer_id')
            ->join('map', 'order_map_id', 'map_id')
            ->select('order.*', 'customer.customer_name', 'customer.customer_email', 'map.map_width', 'map.map_height')
            ->orderBy('order_id', 'DESC')
            ->limit(10)
            ->get();

        $orderStatusBreakdown = DB::table('order')
            ->select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->get();

        return view('admin.dashboard', compact('totalCustomers', 'totalOrders', 'totalRevenue', 'pendingOrders', 'recentOrders', 'orderStatusBreakdown'));
    }

    public function customers()
    {
        $customers = DB::table('customer')
            ->leftJoin('order', 'customer_id', 'order_member_id')
            ->select('customer.*', DB::raw('count(order_id) as total_orders'))
            ->groupBy('customer_id')
            ->paginate(20);

        return view('admin.customers', compact('customers'));
    }

    public function orders()
    {
        $orders = DB::table('order')
            ->join('customer', 'order_member_id', 'customer_id')
            ->join('map', 'order_map_id', 'map_id')
            ->select('order.*', 'customer.customer_name', 'customer.customer_email', 'map.map_width', 'map.map_height', 'map.map_data', 'map.map_frame')
            ->orderBy('order_id', 'DESC')
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'order_status' => 'required|in:Pending,Approved,Completed,Cancelled'
        ]);

        DB::table('order')->where('order_id', $orderId)->update([
            'order_status' => $request->order_status,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function pricing()
    {
        $pricingSettings = [
            'map_multiplier' => Setting::get('map_multiplier', 0.8),
            'text_addon' => Setting::get('text_addon', 4.99),
            'compass_addon' => Setting::get('compass_addon', 4.99),
            'addons_addon' => Setting::get('addons_addon', 9.99),
            'frame_cost_per_inch' => Setting::get('frame_cost_per_inch', 2.50),
        ];

        $frames = scandir(public_path('frames'));
        $frames = array_filter($frames, function($file) {
            return !in_array($file, ['.', '..']) && !is_dir(public_path('frames/' . $file));
        });

        $frameMultipliers = [];
        foreach($frames as $frame) {
            $frameMultipliers[$frame] = Setting::get('frame_multiplier_' . str_replace(' ', '_', $frame), 1.2);
        }

        return view('admin.pricing', compact('pricingSettings', 'frameMultipliers'));
    }

    public function updatePricing(Request $request)
    {
        $validated = $request->validate([
            'map_multiplier' => 'required|numeric|min:0',
            'text_addon' => 'required|numeric|min:0',
            'compass_addon' => 'required|numeric|min:0',
            'addons_addon' => 'required|numeric|min:0',
            'frame_cost_per_inch' => 'required|numeric|min:0',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'number');
        }

        if ($request->has('frame_multipliers')) {
            foreach ($request->frame_multipliers as $frameFile => $multiplier) {
                Setting::set('frame_multiplier_' . str_replace(' ', '_', $frameFile), $multiplier, 'number');
            }
        }

        return redirect()->back()->with('success', 'Pricing settings updated successfully');
    }

    public function profile()
    {
        $admin = auth('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:admin_users,admin_email,' . $admin->admin_id . ',admin_id',
            'admin_phone' => 'nullable|string|max:20',
        ]);

        $admin->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $admin->admin_password)) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        $admin->update([
            'admin_password' => \Illuminate\Support\Facades\Hash::make($validated['new_password'])
        ]);

        return redirect()->back()->with('success', 'Password changed successfully');
    }
}
