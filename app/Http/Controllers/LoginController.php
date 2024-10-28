<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log; 

class LoginController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // public function callbackGoogle()
    // {
    //     try {
    //         $google_user = Socialite::driver('google')->user();
    //         $user = User::where('google_id', $google_user->getId())->first();

    //         // Clear the uploaded CSV data when a new user logs in
    //         \App\Models\CsvData::truncate();
    //         \App\Models\CsvUpload::truncate();

    //         if (!$user) {
    //             $new_user = User::create([
    //                 'name' => $google_user->getName(),
    //                 'email' => $google_user->getEmail(),
    //                 'google_id' => $google_user->getId(),
    //                 'password' => null // Ensure password is nullable
    //             ]);

    //             Auth::login($new_user);
    //             return redirect()->route('welcome');
    //         } else {
    //             Auth::login($user);
    //             return redirect()->route('welcome');
    //         }

    //     } catch (\Throwable $th) {
    //         dd('Something went wrong! ' . $th->getMessage());
    //     }
    // }
    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();
            $user = User::where('google_id', $google_user->getId())->first();
    
            // Clear the uploaded CSV data when a new user logs in
            \App\Models\CsvData::truncate();
            \App\Models\CsvUpload::truncate();
    
            if (!$user) {
                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    'password' => null // Ensure password is nullable
                ]);
    
                Auth::login($new_user);
                return redirect()->route('welcome');
            } else {
                Auth::login($user);
                return redirect()->route('welcome');
            }
    
        } catch (\Throwable $th) {
            // บันทึกข้อผิดพลาดลงใน log ของระบบ
            Log::error('Google Login Error: ' . $th->getMessage());
    
            // ส่งผู้ใช้ไปยังหน้าจอแสดง error พร้อมข้อความที่เป็นมิตร
            return redirect()->route('error.page')->with('error_message', 'ไม่สามารถเข้าสู่ระบบด้วย Google ได้ในขณะนี้ กรุณาลองใหม่อีกครั้ง');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome'); // Redirect to the welcome page or any other page
    }

}
