<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Đây là nơi định nghĩa các route cho API (không qua CSRF)
| Mọi route API có prefix là /api/
|--------------------------------------------------------------------------
*/

// ✅ Route đăng nhập (không cần token)
Route::post('/login', [AuthApiController::class, 'login']);

// ✅ Các route yêu cầu xác thực bằng Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Lấy thông tin user hiện tại
    Route::get('/user', [AuthApiController::class, 'user']);

    // Đăng xuất (xoá token hiện tại)
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // CRUD sản phẩm (được bảo vệ bởi token)
    Route::apiResource('products', ProductController::class);
});
