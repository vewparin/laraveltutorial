<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvUploadController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Authenticate;

Route::get('auth/google', [LoginController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/callback', [LoginController::class, 'callbackGoogle'])->name('auth.google.callback');


Route::get('/login', function () {
    return view('login');
})->name('login');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


// Route::get('/upload', function () {
//     return view('csv.upload');
// })->middleware('auth')->name('csv.upload.form');
Route::middleware('auth')->group(function () {
    Route::get('/upload', [CsvUploadController::class, 'showUploadForm'])->name('csv.upload.form');
    Route::post('/upload', [CsvUploadController::class, 'uploadFile'])->name('csv.upload');
    Route::delete('/upload/{id}', [CsvUploadController::class, 'deleteFile'])->name('csv.delete');
    Route::post('/delete-all', [CsvUploadController::class, 'deleteAllFiles'])->name('csv.delete.all');
    Route::post('/analyze-comments', [CsvUploadController::class, 'analyzeComments'])->name('comments.analyze');
    Route::get('/analysis-results', [CsvUploadController::class, 'showAnalysisResults'])->name('comments.analysis.results');
    Route::post('/delete-all-results', [CsvUploadController::class, 'deleteAllResults'])->name('result.delete.all');
});

Route::post('/save-analysis-results', [AnalysisController::class, 'saveResults'])->name('save.analysis.results');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// web.php
Route::post('/download-csv', [AnalysisController::class, 'downloadCSV'])->name('download.csv');
