<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/store', App\Http\Controllers\StoreController::class);
    Route::resource('/categories', App\Http\Controllers\CategoryController::class);
    Route::resource('/products', App\Http\Controllers\ProductController::class);
    Route::resource('/customers', App\Http\Controllers\CustomerController::class);
    Route::resource('/carts', App\Http\Controllers\CartController::class);
    Route::resource('/transactions', App\Http\Controllers\TransactionController::class);

    Route::get('/api/store', [App\Http\Controllers\StoreController::class, 'api']);
    Route::get('/api/categories', [App\Http\Controllers\CategoryController::class, 'api']);
    Route::get('/api/products', [App\Http\Controllers\ProductController::class, 'api']);
    Route::get('/api/customers', [App\Http\Controllers\CustomerController::class, 'api']);
    Route::get('/api/carts', [App\Http\Controllers\CartController::class, 'api']);
    Route::get('/api/transactions', [App\Http\Controllers\TransactionController::class, 'api']);
    Route::get('/invoice', [App\Http\Controllers\CartController::class, 'invoice']);
    Route::get('/total', [App\Http\Controllers\CartController::class, 'total']);
    Route::get('/getProduct/{id}', [App\Http\Controllers\CartController::class, 'getProduct']);
    Route::get('/getCustomers', [App\Http\Controllers\CartController::class, 'getCustomers']);
    Route::post('/cancel', [App\Http\Controllers\CartController::class, 'cancel']);
    Route::get('/print/{id}', [App\Http\Controllers\TransactionController::class, 'print']);
    Route::get('/profits', [App\Http\Controllers\ProfitController::class, 'index']);
    Route::get('/api/profits', [App\Http\Controllers\ProfitController::class, 'api']);
});

