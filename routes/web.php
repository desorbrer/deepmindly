<?php

use App\Http\Controllers\HaltController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/halt', [HaltController::class, 'index'])->name('halt.index');
Route::post('/halt', [HaltController::class, 'store'])->name('halt.store');
