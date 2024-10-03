<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextAnalysisResult extends Model
{
    use HasFactory;

    protected $fillable = ['comment', 'score', 'polarity', 'processing_time'];
}