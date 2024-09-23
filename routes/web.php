<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\Main;
use App\Http\Controllers\Front\Opearation;
use App\Http\Controllers\Front\Account;

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

Route::get('/login', [Main::class,'login']);
Route::get('/signup', [Main::class,'signup']);
Route::POST('/post-login', [Account::class,'login']);
Route::POST('/post-signup', [Account::class,'register']);

Route::middleware([EnsureLogin::class])->group(function () {
    Route::get('/dashboard', [Main::class,'dashboard']);
    Route::get('/create-map', [Main::class,'index']);
    Route::post('/save-image', [Opearation::class,'save']);
});
