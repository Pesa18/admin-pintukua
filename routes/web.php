<?php

use App\Http\Controllers\ApiArticle;
use App\Http\Controllers\UploadImageEditor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/imageeditor', [UploadImageEditor::class, 'upload'])->name('upload.image.editor');
Route::resource('user', ApiArticle::class);
