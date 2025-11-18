<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('');
});
Route::prefix('kantin')->group(function(){
    Route::get('/ogin', function(){
        return view('login');
    })->name('login.index');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::middleware('auth')->group(function(){
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
