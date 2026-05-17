<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
