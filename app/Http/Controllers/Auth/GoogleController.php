<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str; // Thêm thư viện này
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class GoogleController extends Controller
{
    // Bước 1: Chuyển hướng đến Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Bước 2: Google callback trả về
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();
            $googleId = $googleUser->getId();

            // 1. Tìm người dùng dựa trên EMAIL để xử lý LIÊN KẾT TÀI KHOẢN
            $user = User::where('email', $email)->first();

            if ($user) {
                // Tài khoản đã tồn tại (Đăng ký form hoặc đã liên kết Google trước đó)
                
                // 2. LIÊN KẾT: Cập nhật google_id nếu chưa có
                $user->google_id = $googleId;
                
                // QUAN TRỌNG: Đánh dấu email đã xác minh nếu chưa xác minh
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }
                
                $user->save();

            } else {
                // 3. Người dùng hoàn toàn mới (Tạo tài khoản mới)
                $user = User::create([
                    'google_id' => $googleId,
                    'name' => $googleUser->getName(),
                    'email' => $email,
                    // Đánh dấu email đã xác minh vì Google đã làm điều đó
                    'email_verified_at' => now(), 
                    'profile_photo_path' => $googleUser->getAvatar(),
                    'role' => 'user', 
                    // Tạo mật khẩu ngẫu nhiên để thỏa mãn ràng buộc NOT NULL của DB
                    'password' => Hash::make(Str::random(16)), 
                ]);
            }

            Auth::login($user);

            // Chuyển hướng tới trang dự định hoặc dashboard
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            // Log lỗi để dễ dàng debug
            \Log::error('Google login failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại! Vui lòng thử lại.');
        }
    }
}