<?php

use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\ShoppingSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\CartItemController;
use App\Http\Controllers\v1\OrderController;

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


Route::get('/shop', [ProductController::class, 'index'])->name('shop'); # This is the correct way to use controllers

Route::get('/', [ProductController::class, 'homePage'])->name('home');

Route::get('/posts/{id}', function ($id) {
    return response('Hello, World ' . $id, 200);
})->where('id', '[0-9]+');;

Route::get('/registration', function () {
    return view('registration');
});
Route::get('/admin', [ProductController::class, 'admin'])->middleware('auth', 'can:admin')->name('admin');

Route::get('/admin_product/{id}',[ProductController::class, 'adminProduct']);

Route::get('/admin_product', [ProductController::class, 'emptyProduct']);

Route::get('/login', function () {
    return view('login');
});

Route::get('/profile', [UserController::class, 'profile'])->middleware('auth', 'can:temporary-profile');

Route::get('/logout', [UserController::class, 'logout']);

Route::post('/login-submit', [UserController::class, 'login']);

Route::post('/registration-submit', [UserController::class, 'registration']);

Route::get('/single-page/{id}', [ProductController::class, 'singlePage']);

Route::get('/cart', [UserController::class, 'temporaryAccount']);

Route::get('/cart-items/count', [CartItemController::class, 'numberOfItems']);

Route::get('/process-order', [OrderController::class, 'processOrder'])->middleware('auth');

Route::post('/create-order', [OrderController::class, 'createOrder'])->middleware('auth');

Route::delete('/product/delete/{id}', [ProductController::class, 'deleteProduct'])->middleware('auth', 'can:admin');

Route::delete('/cart-items/delete/multiple', [ProductController::class, 'deleteProductMultiple'])->middleware('auth', 'can:admin');

Route::match(['put', 'post'], '/product/update', [ProductController::class, 'updateProduct'])
    ->middleware('auth', 'can:admin');

Route::get('/about_us', function () {
    return view('about_us');
});
