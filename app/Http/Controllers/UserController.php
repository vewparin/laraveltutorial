<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // public function showUsers()
    // {
    //     // ดึงข้อมูลผู้ใช้ทั้งหมดที่มีการเข้าสู่ระบบด้วย Google
    //     $users = User::whereNotNull('google_id')->get();
    //     return view('admin.users', compact('users'));
    // }
    public function showUsers(Request $request)
    {
        // ตรวจสอบว่ามีคำค้นหาหรือไม่
        $search = $request->input('search');

        if ($search) {
            // ค้นหาผู้ใช้ที่มี google_id และตรงกับคำค้นหาชื่อหรืออีเมล
            $users = User::whereNotNull('google_id')
                ->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })->get();
        } else {
            // ดึงข้อมูลผู้ใช้ทั้งหมดที่มี google_id
            $users = User::whereNotNull('google_id')->get();
        }

        return view('admin.users', compact('users', 'search'));
    }

    public function deleteUser($id)
    {
        // ลบผู้ใช้ตาม ID
        User::where('id', $id)->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        // อัปเดตข้อมูลผู้ใช้ เช่น การตั้งค่า role หรือ status
        $user = User::find($id);
        $user->role = $request->input('role');
        $user->status = $request->input('status');
        $user->save();

        return back()->with('success', 'User updated successfully.');
    }
}
