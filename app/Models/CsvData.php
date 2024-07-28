<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    use HasFactory;

    protected $table = 'csv_data'; // ชื่อตารางในฐานข้อมูล

    protected $fillable = [
        'column1',
        'column2',
        'column3',
        // เพิ่มคอลัมน์อื่น ๆ ที่จำเป็น
    ];
}
