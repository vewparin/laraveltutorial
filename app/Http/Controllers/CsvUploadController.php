<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CsvUpload;
use App\Models\CsvData; // ใช้ Model CsvData
use League\Csv\Reader;

class CsvUploadController extends Controller
{
    public function showUploadForm()
    {
        $uploads = CsvUpload::all();
        $csvData = CsvData::all();  // ดึงข้อมูลจากตาราง csv_data
        return view('upload', compact('uploads', 'csvData'));
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->store('uploads');

        // บันทึกข้อมูลไฟล์ในฐานข้อมูล
        $csvUpload = new CsvUpload();
        $csvUpload->file_name = $file->getClientOriginalName();
        $csvUpload->file_path = $filePath;
        $csvUpload->save();

        // อ่านข้อมูลจากไฟล์ CSV และบันทึกลงในตาราง csv_data
        $csv = Reader::createFromPath(storage_path('app/' . $filePath), 'r');

        // กำหนดชื่อคอลัมน์เอง
        $header = ['column1', 'column2', 'column3']; // เพิ่มคอลัมน์ตามที่ต้องการ
        $records = $csv->getRecords($header);

        foreach ($records as $record) {
            CsvData::create([
                'column1' => $record['column1'],  // เปลี่ยนชื่อคอลัมน์ให้ตรงกับตาราง csv_data
                'column2' => $record['column2'],
                'column3' => $record['column3'],
                // เพิ่มคอลัมน์อื่น ๆ ตามโครงสร้างของไฟล์ CSV
            ]);
        }

        return back()->with('success', 'File uploaded and data imported successfully')->with('file', $filePath);
    }
}
