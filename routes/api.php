<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\StudentController;
use Dotenv\Store\File\Paths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('books', BookController::class);

Route::apiResource('students', StudentController::class);

Route::apiResource('loans', LoanController::class);
Route::patch('/loans/{loan}/return', [LoanController::class, 'returnbook']);