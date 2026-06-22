<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    if (Auth::check()) {
        if (in_array(Auth::user()->role, ['admin', 'guru'])) {
            return redirect()->route('guru.dashboard');
        }
        return redirect()->route('siswa.dashboard');
    }
    
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

    
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'guru'])->name('dashboard');
    });

    
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'siswa'])->name('dashboard');

        Route::get('/portfolio/create', [\App\Http\Controllers\PortfolioController::class, 'create'])->name('portfolio.create');
        Route::post('/portfolio', [\App\Http\Controllers\PortfolioController::class, 'store'])->name('portfolio.store');
        
        Route::get('/portfolio/{portfolio}/edit', [\App\Http\Controllers\PortfolioController::class, 'edit'])->name('portfolio.edit');
        Route::put('/portfolio/{portfolio}', [\App\Http\Controllers\PortfolioController::class, 'update'])->name('portfolio.update');
        Route::delete('/portfolio/{portfolio}', [\App\Http\Controllers\PortfolioController::class, 'destroy'])->name('portfolio.destroy');
        Route::get('/portfolio/export-pdf', [\App\Http\Controllers\PortfolioController::class, 'exportPdf'])->name('portfolio.export');
    });

});