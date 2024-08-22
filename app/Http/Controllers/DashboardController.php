<?php

namespace App\Http\Controllers;

use App\Models\AnalysisResult;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    // public function index()
    // {
    //     // ดึงข้อมูลผลลัพธ์จากฐานข้อมูล
    //     $results = AnalysisResult::all(); // เปลี่ยนชื่อโมเดลนี้ตามที่คุณใช้
    //     return view('dashboard', compact('results'));
    // }

    public function getData()
    {
        $results = AnalysisResult::all();

        $polarityCounts = [
            'positive' => $results->where('polarity', 'positive')->count(),
            'neutral' => $results->where('polarity', 'neutral')->count(),
            'negative' => $results->where('polarity', 'negative')->count()
        ];

        return response()->json([
            'polarityCounts' => $polarityCounts,
            'total' => $results->count()
        ]);
    }
}
