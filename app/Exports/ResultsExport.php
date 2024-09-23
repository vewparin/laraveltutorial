<?php

namespace App\Exports;

use App\Models\AnalysisResult;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ResultsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        // ดึงข้อมูลจาก AnalysisResult
        return AnalysisResult::select('comment', 'score', 'polarity', 'processing_time')->get();
    }

    // เพิ่ม header ให้กับไฟล์ Excel
    public function headings(): array
    {
        return [
            'Comment',
            'Score',
            'Polarity',
            'Processing Time (seconds)',
        ];
    }
}
