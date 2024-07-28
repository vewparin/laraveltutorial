<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\sentimentController;
use App\Http\Controllers\CsvUploadController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/sentiments', [sentimentController::class, 'index']);

Route::get('/upload', [CsvUploadController::class, 'showUploadForm'])->name('csv.upload.form');
Route::post('/upload', [CsvUploadController::class, 'uploadFile'])->name('csv.upload');
