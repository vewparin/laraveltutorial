<?php

namespace App\Http\Controllers;
use App\Models\AnalysisResult;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // ดึงข้อมูลผลลัพธ์จากฐานข้อมูล
        $results = AnalysisResult::all(); // เปลี่ยนชื่อโมเดลนี้ตามที่คุณใช้
        return view('dashboard', compact('results'));
    }
}
