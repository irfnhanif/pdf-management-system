<?php

use App\Http\Controllers\PdfFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('pdf')->name('pdf.')->group(function () {
    Route::post('/generate', [PdfFileController::class, 'generate'])->name('generate');
    Route::post('/upload', [PdfFileController::class, 'store'])->name('store');
    Route::get('/list', [PdfFileController::class, 'index'])->name('index');
    Route::delete('/{id}', [PdfFileController::class, 'destroy'])->name('destroy');
});
