<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicPortfolioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\PortfolioController;
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


Route::get('/p/{slug}', [PublicPortfolioController::class, 'show'])
    ->name('portfolio.public');

Route::get('/u/{slug}', [PublicPortfolioController::class, 'profile'])
    ->name('portfolio.profile');

Route::middleware('auth')->group(function () {

    // KELOMPOK GURU
    Route::prefix('guru')->name('guru.')->middleware('role:admin,guru')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard');
        
        // Hapus kata 'guru/' di URL dan 'guru.' di nama rute karena sudah diwakili oleh grup di atasnya
        Route::get('/profile', [ProfileController::class, 'guruShow'])->name('profile');
        Route::put('/profile-update', [ProfileController::class, 'guruUpdate'])->name('profile.update');
        Route::put('/password-update', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });

    // KELOMPOK SISWA
    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'siswa'])->name('dashboard');

        Route::get('/portfolio/print',  [PortfolioController::class, 'printView'])->name('portfolio.print');
        Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
        Route::post('/portfolio',       [PortfolioController::class, 'store'])->name('portfolio.store');

        Route::get('/portfolio/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
        Route::put('/portfolio/{portfolio}',      [PortfolioController::class, 'update'])->name('portfolio.update');
        Route::delete('/portfolio/{portfolio}',   [PortfolioController::class, 'destroy'])->name('portfolio.destroy');

        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    });
        
    
});