<?php

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
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
Route::get('/verifyOtp', [UserController::class, 'VerifyOtpPage']);
Route::get('/logout', [UserController::class, 'logout']);
Route::get('/profile', [UserController::class, 'userProfilePage'])->middleware([TokenVerifyMiddleware::class]);
Route::get('/userProfile', [UserController::class, 'userProfile'])->middleware([TokenVerifyMiddleware::class]);

//User Api Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/otp-send', [UserController::class, 'otpSend']);
Route::post('/otp-verify', [UserController::class, 'otpVerify']);
Route::post('/update-profile', [UserController::class, 'updateProfile'])->middleware([TokenVerifyMiddleware::class]);



// Web Route Group

Route::get('/resetPassword', [UserController::class, 'resetPasswordPage'])->middleware([TokenVerifyMiddleware::class]);


// Api Route Group with Middleware

Route::post('/reset-pass', [UserController::class, 'resetPassword']);
// Admin Route
Route::get('/dashboard', [AdminController::class, 'adminPage'])->middleware([TokenVerifyMiddleware::class]);



// Category api
Route::middleware([TokenVerifyMiddleware::class])->group(function () {
    Route::get('categoryList', [CategoryController::class, 'categoryPage']);
    Route::get('/category', [CategoryController::class, 'categoryList']);
    Route::post('/add-category', [CategoryController::class, 'addCategory']);
    Route::delete('/delete-category', [CategoryController::class, 'deleteCategory']);
    Route::get('/category-id', [CategoryController::class, 'categoryId']);
    Route::put('/update-category', [CategoryController::class, 'updateCategory']);
});




Route::middleware([TokenVerifyMiddleware::class])->group(function () {
    Route::get("/customerList", [customerController::class, 'customerList']);
    Route::get("/customers", [customerController::class, 'customerPage']);
    Route::post("/add-customer", [customerController::class, 'addCustomer']);
    Route::delete("/delete-customer", [customerController::class, 'customerDelete']);
    Route::put("/update-customer", [customerController::class, 'customerUpdate']);
    Route::post("/customer-id", [customerController::class, 'customerByID']);
});


Route::middleware([TokenVerifyMiddleware::class])->group(function () {
    Route::get('/categories', [ProductController::class, 'getCategories']);
    Route::get("/product", [ProductController::class, 'productPage']);
    Route::get('/productList', [ProductController::class, 'productList']);
    Route::post('/add-product', [ProductController::class, 'addProduct']);
    Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct']); // Use {id} to match with the product
    Route::post('/update-product/{id}', [ProductController::class, 'updateProduct']); // Use {id} to match with the product
    Route::get('/product/{id}', [ProductController::class, 'productById']);
});



// Invoice
Route::middleware([TokenVerifyMiddleware::class])->group(function () {
    Route::post("/invoice-create", [InvoiceController::class, 'invoiceCreate']);
    Route::get("/invoice-select", [InvoiceController::class, 'invoiceSelect']);
    Route::post("/invoice-details", [InvoiceController::class, 'InvoiceDetails']);
    Route::post("/invoice-delete", [InvoiceController::class, 'invoiceDelete']);
});
