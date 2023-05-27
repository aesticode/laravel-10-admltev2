<?php

use App\Http\Controllers\{
    AuthController,
    DashboardController,
};

use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'index')->name('login')->middleware('guest');
    Route::post('auth', 'auth')->name('auth')->middleware('guest');
    Route::get('logout', 'logout')->name('logout');
});

Route::get('/', function() {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
