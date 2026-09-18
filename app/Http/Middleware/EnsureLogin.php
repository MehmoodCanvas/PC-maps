<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $customer = Auth::guard('customer')->user();

        // Reading ->customer_id straight off a null user turned every expired
        // session into a 500 with an HTML body, which the map builder could
        // only report as a generic "error saving map".
        if (!$customer || empty($customer->customer_id)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'unauthenticated',
                    'message' => 'Your session has expired. Please sign in again.',
                ], 401);
            }

            return redirect('login');
        }

        return $next($request);
    }
}
