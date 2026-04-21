<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\Main;
use App\Http\Controllers\Front\Opearation;
use App\Http\Controllers\Front\Account;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Middleware\EnsureLogin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return redirect(url('/login'));
});

Route::get('/login', [Main::class,'login']);
Route::get('/signup', [Main::class,'signup']);
Route::get('/checkout', [Main::class,'checkout']);
Route::POST('/post-login', [Account::class,'login']);
Route::POST('/post-signup', [Account::class,'register']);

Route::middleware([EnsureLogin::class])->group(function () {
    Route::get('/dashboard', [Main::class,'dashboard']);
    Route::get('/create-map', [Main::class,'index']);
    Route::get('/map-detail', [Main::class, 'mapDetail']);
    Route::post('/save-image', [Opearation::class,'save']);
    Route::post('/upload-logo', [Opearation::class,'uploadLogo']);
    Route::post('/update-map-frame', [Opearation::class, 'updateFrame']);
    Route::post('/post-create-order', [Opearation::class,'createOrder']);
    Route::post('/orders/{id}/capture', [Opearation::class,'captureOrder']);
    Route::post('/update-profile', [Account::class,'updateProfile']);
    Route::post('/upload-profile-picture', [Account::class,'uploadProfilePicture']);
});
Route::get('/logout', function () {
     session()->flush();
     return redirect(url('/login'));

});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/post-login', [AdminAuthController::class, 'postLogin']);
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/customers', [AdminDashboardController::class, 'customers'])->name('admin.customers');
        Route::get('/orders', [AdminDashboardController::class, 'orders'])->name('admin.orders');
        Route::post('/orders/{id}/status', [AdminDashboardController::class, 'updateOrderStatus'])->name('admin.update-order-status');
        Route::get('/pricing', [AdminDashboardController::class, 'pricing'])->name('admin.pricing');
        Route::post('/pricing', [AdminDashboardController::class, 'updatePricing'])->name('admin.update-pricing');
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('admin.profile');
        Route::post('/profile', [AdminDashboardController::class, 'updateProfile'])->name('admin.update-profile');
        Route::post('/change-password', [AdminDashboardController::class, 'changePassword'])->name('admin.change-password');
        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});