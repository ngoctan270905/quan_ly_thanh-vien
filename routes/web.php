<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

# Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// routes/web.php
Route::get('/products-frontend', function () {
    return view('products.index');
});
// 💡 ROUTE MỚI CHO TRANG TẠO SẢN PHẨM
Route::get('/products/create', function () {
    return view('products.create');
})->middleware(['auth', 'verified'])->name('products.create');

