<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackApp\UserController;
use App\Http\Controllers\BackApp\CategoryController;
use App\Http\Controllers\BackApp\DocumentController;
use App\Http\Controllers\BackApp\DashboardController;
use App\Http\Controllers\BackApp\FiscalYearController;

Route::get('/', function () {
    return to_route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('document', DocumentController::class);
    Route::get('/document/download/{id}', [DocumentController::class,'downloadFile'])->name('document.download');
    
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('category', CategoryController::class);
        Route::resource('fiscal_year', FiscalYearController::class);
        Route::resource('user', UserController::class);
    });
});
