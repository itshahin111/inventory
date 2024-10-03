<?php

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\TokenVerifyMiddleware;

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
// User Web Routes
Route::get('/userLogin', [UserController::class, 'LoginPage']);
Route::get('/registration', [UserController::class, 'RegistrationPage']);
Route::get('/sendOtp', [UserController::class, 'SendOtpPage']);
Route::get('/verifyOtp', [UserController::class, 'VerifyOTPPage']);
Route::get('/logout', [UserController::class, 'logout']);

//User Api Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/otp-send', [UserController::class, 'otpSend']);
Route::post('/otp-verify', [UserController::class, 'otpVerify']);



// Web Route Group

Route::get('/resetPassword', [UserController::class, 'resetPasswordPage'])->middleware([TokenVerifyMiddleware::class]);
Route::get('/categoryList', [CategoryController::class, 'categoryList'])->middleware([TokenVerifyMiddleware::class]);
Route::get("/list-product", [ProductController::class, 'ProductList'])->middleware([TokenVerifyMiddleware::class]);




// Api Route Group with Middleware
// Route::middleware(['TokenVerifyMiddleware'])->group(function () {
Route::post('/reset-pass', [UserController::class, 'resetPassword']);
// Admin Route
Route::get('/dashboard', [AdminController::class, 'adminPage'])->middleware([TokenVerifyMiddleware::class]);
// Category api
Route::post('/addCategory', [CategoryController::class, 'addCategory'])->middleware([TokenVerifyMiddleware::class]);
Route::delete('/deleteCategory', [CategoryController::class, 'deleteCategory'])->middleware([TokenVerifyMiddleware::class]);
Route::put('/updateCategory', [CategoryController::class, 'updateCategory'])->middleware([TokenVerifyMiddleware::class]);
Route::post('/editCategory', [CategoryController::class, 'editCategory'])->middleware([TokenVerifyMiddleware::class]);
Route::post("/addProduct", [ProductController::class, 'addProduct'])->middleware([TokenVerifyMiddleware::class]);

// });
