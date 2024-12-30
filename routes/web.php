<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;

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

Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
Route::post('/transactions/add-to-cart', [TransactionController::class, 'addToCart'])->name('transactions.addToCart');
Route::get('/transactions/cart', [TransactionController::class, 'showCart'])->name('transactions.cart');
Route::post('/transactions/complete', [TransactionController::class, 'completeTransaction'])->name('transactions.complete');
Route::post('/transactions/remove-from-cart/{productId}', [TransactionController::class, 'removeFromCart'])->name('transactions.removeFromCart');
Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancelTransaction'])->name('transactions.cancel');

Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class)->middleware('auth');
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
});

Route::get('reports/sales', [ProductController::class, 'salesReport'])->name('reports.sales');
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');


Route::fallback(function () {
    return redirect()->back();
});


