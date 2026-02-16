<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PdfToolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('pdf-tool')->middleware(['auth'])->group(function () {
    Route::get('/', [PdfToolController::class, 'index'])->name('pdf-tool.index');
    Route::post('/upload', [PdfToolController::class, 'upload'])->name('pdf-tool.upload');
    Route::get('/preview', [PdfToolController::class, 'previewPage'])->name('pdf-tool.preview');
    Route::post('/process', [PdfToolController::class, 'process'])->name('pdf-tool.process');
    Route::get('/download', [PdfToolController::class, 'download'])->name('pdf-tool.download');
    Route::post('/download-zip', [PdfToolController::class, 'downloadZip'])->name('pdf-tool.download-zip');
});

require __DIR__.'/auth.php';
