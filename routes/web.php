<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvUploadController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Authenticate;
use App\Exports\ResultsExport;
use App\Http\Controllers\ApiSettingsController;
use App\Http\Controllers\ModelSettingsController;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\TextAnalysisController;
use App\Http\Controllers\TextDashboardController;
use App\Http\Controllers\UserController;

Route::get('/admin/users', [UserController::class, 'showUsers'])->name('admin.users')->middleware('auth');
Route::post('/admin/users/delete/{id}', [UserController::class, 'deleteUser'])->name('admin.users.delete')->middleware('auth');
Route::post('/admin/users/update/{id}', [UserController::class, 'updateUser'])->name('admin.users.update')->middleware('auth');
Route::put('/admin/users/update/{id}', [UserController::class, 'updateUser'])->name('admin.users.update');
Route::delete('/admin/users/delete/{id}', [UserController::class, 'deleteUser'])->name('admin.users.delete');

Route::get('auth/google', [LoginController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/callback', [LoginController::class, 'callbackGoogle'])->name('auth.google.callback');

Route::get('/error', function () {
    return view('error');
})->name('error.page');

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
    Route::get('/input-text', [TextAnalysisController::class, 'showInputForm'])->name('input.text.form');
    Route::post('/process-text', [TextAnalysisController::class, 'processText'])->name('process.text');

    // Route สำหรับลบข้อมูลทั้งหมด
    Route::post('/input-text-delete-all-results', [TextAnalysisController::class, 'deleteAll'])->name('input.text.delete.all.results');
    // Route สำหรับลบข้อมูลทีละแถว
    Route::delete('/delete-result/{id}', [TextAnalysisController::class, 'delete'])->name('delete.result');

    Route::get('/text-dashboard-data', [TextAnalysisController::class, 'showInputTextForm'])->name('text.to.dashboard.data');
});

Route::post('/save-analysis-results', [AnalysisController::class, 'saveResults'])->name('save.analysis.results');
//อันเก่า
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/download-csv', [AnalysisController::class, 'downloadCSV'])->name('download.csv');

Route::get('/previous-results', [CsvUploadController::class, 'showPreviousResults'])->name('comments.previous.results');

//อันใหม่
Route::get('/dashboard-data', [DashboardController::class, 'getData'])->name('dashboard.data');

Route::get('/download-excel', function () {
    return Excel::download(new ResultsExport, 'analysis_results.xlsx');
})->name('download.excel');


Route::get('/admin/api-settings', [ApiSettingsController::class, 'showSettings'])->name('api.settings')->middleware('auth');
Route::post('/admin/api-settings/update', [ApiSettingsController::class, 'updateSettings'])->name('api.settings.update')->middleware('auth');
