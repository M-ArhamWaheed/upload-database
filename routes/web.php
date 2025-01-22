<?php

use App\Http\Controllers\DatabaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/upload-database', 'main');

Route::post('/upload-database', [DatabaseController::class, 'uploadDatabase'])->name('upload.database');

