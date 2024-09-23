<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvUpload extends Model
{
    use HasFactory;
    protected $fillable = ['file_name', 'file_path', 'user_token'];
}
