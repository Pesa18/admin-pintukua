<?php

use App\Filament\Pages\LaporanKinerja;
use App\Http\Controllers\ApiArticle;
use App\Http\Controllers\UploadImageEditor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/imageeditor', [UploadImageEditor::class, 'upload'])->name('upload.image.editor');

Route::post('/getlaporan', [LaporanKinerja::class, 'getall'])->name('laporan.kinerja');
Route::post('/cetakpdf', [LaporanKinerja::class, 'cetakPdf'])->name('cetak.laporan');
Route::resource('user', ApiArticle::class);
