<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerifyMiddleware;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('/registration', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/otp-send', [UserController::class, 'otpSend']);
Route::post('/otp-verify', [UserController::class, 'otpVerify']);
Route::post('/reset-pass', [UserController::class, 'resetPassword'])->middleware([TokenVerifyMiddleware::class]);