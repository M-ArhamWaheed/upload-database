<?php

use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('addSqlData', [DatabaseController::class , 'sendSqlFile'] )

Route::post('upload-database', [ExportController::class, 'uploadDatabase'])->name('upload-database');
