<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    // Đăng nhập bằng API
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        $user = Auth::user();

        // Xoá token cũ (tùy chọn)
        $user->tokens()->delete();

        // Tạo token mới
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công',
        ]);
    }

    // Lấy thông tin user hiện tại
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
