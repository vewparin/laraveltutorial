<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiSettingsController extends Controller
{
    public function showSettings()
    {
        // โหลดการตั้งค่าจากไฟล์ที่เก็บไว้ (เช่น JSON)
        $apiSettings = json_decode(Storage::get('api_settings.json'), true);

        // กำหนด API URL ที่กำลังใช้งาน
        $currentApiUrl = 'https://api.aiforthai.in.th/ssense';
        $currentApiKey = $apiSettings['api_key'] ?? 'Not Set';
        $currentModelFile = $apiSettings['model_file_path'] ?? 'No model uploaded';

        return view('admin.api-settings', compact('currentApiUrl', 'currentApiKey', 'currentModelFile'));
    }

    public function updateSettings(Request $request)
    {
        // ตรวจสอบข้อมูลในฟอร์ม
        $request->validate([
            'api_key' => 'nullable|string|max:255',
            'model_file' => 'nullable|file|mimes:zip,tar|max:10240', // อนุญาตให้เป็นไฟล์ .zip หรือ .tar ขนาดไม่เกิน 10MB
        ]);

        // ตรวจสอบและบันทึกการอัปโหลดไฟล์โมเดล
        if ($request->hasFile('model_file')) {
            $modelFilePath = $request->file('model_file')->store('models');
        } else {
            $modelFilePath = null;
        }

        // รับข้อมูลจากฟอร์มและบันทึกเป็น JSON
        $apiSettings = [
            'api_key' => $request->input('api_key'),
            'model_file_path' => $modelFilePath,
        ];

        Storage::put('api_settings.json', json_encode($apiSettings));

        return back()->with('success', 'Settings updated successfully.');
    }
}
