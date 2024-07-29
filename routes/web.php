<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvUploadController;
use App\Http\Controllers\AnalysisController;
Route::get('/', function () {
    return view('welcome');
})->name('welcome');



Route::get('/upload', [CsvUploadController::class, 'showUploadForm'])->name('csv.upload.form');
Route::post('/upload', [CsvUploadController::class, 'uploadFile'])->name('csv.upload');
Route::delete('/upload/{id}', [CsvUploadController::class, 'deleteFile'])->name('csv.delete');
Route::post('/delete-all', [CsvUploadController::class, 'deleteAllFiles'])->name('csv.delete.all');

Route::post('/analyze-comments', [CsvUploadController::class, 'analyzeComments'])->name('comments.analyze');
Route::get('/analysis-results', [CsvUploadController::class, 'showAnalysisResults'])->name('comments.analysis.results');

Route::post('/save-analysis-results', [AnalysisController::class, 'saveResults'])->name('save.analysis.results');

