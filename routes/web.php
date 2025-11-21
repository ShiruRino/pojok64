<?php

use App\Http\Controllers\GuestController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;


Route::name('user.')->group(function () {

    // Home
    Route::get('/', function () {
        $products = Product::all();
        return view('bfc.pages.home', compact('products'));
    })->name('home');

    // Menu
    Route::get('/menu', function () {
        $products = Product::all();
        return view('bfc.pages.menu', compact('products'));
    })->name('menu');

    // Create order
    Route::get('/order', function (Request $request) {
        $product_id = Product::where('slug', $request->query('slug'))->value('id');
        $products = Product::all();
        return view('bfc.pages.create', compact('products', 'product_id'));
    })->name('order.create');

    Route::post('/order/store', [GuestController::class, 'store'])
        ->name('order.store');

    // Order detail page
    Route::get('/order/{code}', function ($code) {
        $order = Order::where('code', $code)->first();
        return view('bfc.pages.index', compact('order'));
    })->name('order.index');
    Route::patch('/order/{order}/cancel', [GuestController::class, 'guestCancel'])->name('order.guestCancel');

    Route::patch('/order/{order}/update-all', [GuestController::class, 'updateAllQuantities'])
    ->name('order.updateAll');


    // Product detail
    Route::get('/product/{slug}', function ($slug) {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('bfc.pages.show', compact('product'));
    })->name('show');

});



Route::prefix('kantin')->group(function(){
    Route::get('login', function(){
        return view('login');
    })->name('login.index');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::middleware('auth')->group(function(){
        Route::get('dashboard', function(){
            $productCount = Product::count();
            $orderCount = Order::count();
            $transactionCount = Transaction::count();
            return view('dashboard', compact('productCount', 'orderCount', 'transactionCount'));
        })->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('transactions', TransactionController::class);
        Route::post('/transactions-print-all', [TransactionController::class, 'printAll'])->name('transactions.laporan');
        Route::post('/transactions-generate-receipt/{id}', [TransactionController::class, 'generateReceipt'])->name('transactions.generateReceipt');
    });
});
