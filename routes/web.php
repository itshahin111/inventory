<?php

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

//User Api Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/otp-send', [UserController::class, 'otpSend']);
Route::post('/otp-verify', [UserController::class, 'otpVerify']);



// Web Route Group
Route::middleware(['TokenVerifyMiddleware'])->group(function () {
    Route::get('/resetPassword', [UserController::class, 'resetPasswordPage']);
    Route::get('/categoryList', [CategoryController::class, 'categoryList']);
    Route::get("/list-product", [ProductController::class, 'ProductList']);

});


// Api Route Group with Middleware
Route::middleware(['TokenVerifyMiddleware'])->group(function () {
    Route::post('/reset-pass', [UserController::class, 'resetPassword']);
    // Admin Route
    Route::get('/dashboard', [AdminController::class, 'adminPage']);
    // Category api
    Route::post('/addCategory', [CategoryController::class, 'addCategory']);
    Route::delete('/deleteCategory', [CategoryController::class, 'deleteCategory']);
    Route::put('/updateCategory', [CategoryController::class, 'updateCategory']);
    Route::post('/editCategory', [CategoryController::class, 'editCategory']);
    Route::post("/addProduct", [ProductController::class, 'addProduct']);

});
