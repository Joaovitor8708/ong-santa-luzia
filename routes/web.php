<?php

use App\Http\Controllers\IdosaController;
use App\Http\Controllers\PlanoIndividualController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\TermoAbrigamentoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::resource('idosas', IdosaController::class);
    Route::resource('responsaveis', ResponsavelController::class);
    Route::resource('planos', PlanoIndividualController::class);
    Route::resource('termos', TermoAbrigamentoController::class);
});
    
//require __DIR__.'/auth.php';