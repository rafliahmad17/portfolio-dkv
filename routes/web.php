<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicPortfolioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// ---------------------------------------------------------------
// PUBLIC ROUTE — Tidak membutuhkan autentikasi.
// Dapat diakses oleh siapa saja (pengunjung umum, rekruter, dll.)
// melalui URL langsung atau scan QR Code.
// ---------------------------------------------------------------
Route::get('/p/{slug}', [PublicPortfolioController::class, 'show'])
    ->name('portfolio.public');

Route::middleware('auth')->group(function () {

    Route::prefix('guru')->name('guru.')->middleware('role:admin,guru')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'guru'])->name('dashboard');
    });

    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'siswa'])->name('dashboard');

        Route::get('/portfolio/print',  [\App\Http\Controllers\PortfolioController::class, 'printView'])->name('portfolio.print');
        Route::get('/portfolio/create', [\App\Http\Controllers\PortfolioController::class, 'create'])->name('portfolio.create');
        Route::post('/portfolio',       [\App\Http\Controllers\PortfolioController::class, 'store'])->name('portfolio.store');

        Route::get('/portfolio/{portfolio}/edit', [\App\Http\Controllers\PortfolioController::class, 'edit'])->name('portfolio.edit');
        Route::put('/portfolio/{portfolio}',      [\App\Http\Controllers\PortfolioController::class, 'update'])->name('portfolio.update');
        Route::delete('/portfolio/{portfolio}',   [\App\Http\Controllers\PortfolioController::class, 'destroy'])->name('portfolio.destroy');
    });

});