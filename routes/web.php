<?php

use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\JWTtokenVarificationMiddleware;

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
Route::view('/', 'pages.auth.login-page');
//Backend Api
Route::post('/userRegistration', [UserController::class, 'userRegistration']);
Route::post('/userLogin', [UserController::class, 'userLogin']);
Route::post('/sent-otp', [UserController::class, 'OtpCodeSend']);
Route::post('/varify-otp', [UserController::class, 'varifyOtp']);

//token varification
Route::post('/password-reset', [UserController::class, 'passWordReset'])->middleware(JWTtokenVarificationMiddleware::class);
Route::get('/userLogin-page', [UserController::class, 'userLoginPage']);
Route::get('/userRegistration-page', [UserController::class, 'userRegistrationPage']);

Route::group(['middleware' => ['jtwTokenVarify']], function () {

    Route::get('/sentOTP', [UserController::class, 'sentOTPPage']);
    Route::get('/varifyOTP', [UserController::class, 'varifyOtpPage']);
    Route::get('/resetPassword', [UserController::class, 'reserPasswordPage']);
    //Dashboard
    Route::get('/dashboard', [UserController::class, 'Dashboard']);
    Route::get('/userLogout', [UserController::class, 'userLogout']);
    //profile page
    Route::get('/profile', [UserController::class, 'profilePage']);
    Route::get('/userProfileDetails', [UserController::class, 'userProfile']);
    Route::post('/userProfileUpdate', [UserController::class, 'userProfileUpdate']);

});

//Category
Route::group(['middleware' => ['jtwTokenVarify']], function () {

    Route::view('/CategoryPage', "pages.dashboard.category-page");
    Route::get('/categoryList', [CategoryController::class, 'categoryList']);
    Route::post('/CategoryCreate', [CategoryController::class, 'CategoryCreate']);
    Route::post('/CategoryUpdate', [CategoryController::class, 'CategoryUpdate']);
    Route::post('/CategoryDelete', [CategoryController::class, 'CategoryDelete']);
    Route::post('/CategoryByID', [CategoryController::class, 'CategoryByID']);

});

Route::group(['middleware' => ['jtwTokenVarify']], function () {
    
    Route::view('/CustomerPage', "pages.dashboard.customer-page");
    Route::get('/CustomerList', [CustomerController::class, 'CustomerList']);
    Route::post('/CustomerCreate', [CustomerController::class, 'CustomerCreate']);
    Route::post('/CustomerUpdate', [CustomerController::class, 'CustomerUpdate']);
    Route::post('/CustomerDelete', [CustomerController::class, 'CustomerDelete']);
    Route::post('/CustomerByID', [CustomerController::class, 'CustomerByID']);
    
});

Route::group(['middleware' => ['jtwTokenVarify']], function () {
    
    Route::view('/ProductPage', "pages.dashboard.product-page");
    Route::get('/ProductList', [ProductController::class, 'ProductList']);
    Route::post('/ProductCreate', [ProductController::class, 'ProductCreate']);
    Route::post('/ProductUpdate', [ProductController::class, 'ProductUpdate']);
    Route::post('/ProductDelete', [ProductController::class, 'ProductDelete']);
    Route::post('/ProductByID', [ProductController::class, 'ProductByID']);
    
});

Route::group(['middleware' => ['jtwTokenVarify']], function () {
    
    Route::view('/ProductSale', "pages.dashboard.sale-page");
    Route::view('/InvoicePage', "pages.dashboard.invoice-page");
    
    Route::post('/InvoiceCreate', [InvoiceController::class, 'InvoiceCreate']);
    Route::get('/InvoiceSelect', [InvoiceController::class, 'InvoiceSelect']);
    Route::post('/InvoiceDetails', [InvoiceController::class, 'InvoiceDetails']);
    Route::post('/InvoiceDelete', [InvoiceController::class, 'InvoiceDelete']);
    Route::get('/Summery', [InvoiceController::class, 'Summery']);
});

Route::group(['middleware' => ['jtwTokenVarify']], function () {
    
    Route::view('/ReportPage', "pages.dashboard.report-page");
   Route::get('/sales-report/{FormDate}/{ToDate}',[ReportController::class,'report']);
});