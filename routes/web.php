<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicPortfolioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\StudentController;

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
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');

    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


Route::get('/p/{slug}', [PublicPortfolioController::class, 'show'])
    ->name('portfolio.public');

Route::get('/u/{slug}', [PublicPortfolioController::class, 'profile'])
    ->name('portfolio.profile');

// PDF ringkas publik — bisa dipindai/diakses tanpa login lewat slug siswa
Route::get('/u/{slug}/print', [PublicPortfolioController::class, 'print'])
    ->name('portfolio.public.print');

Route::middleware('auth')->group(function () {

    // KELOMPOK GURU
    Route::prefix('guru')->name('guru.')->middleware('role:admin,guru')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard');
        
        // Hapus kata 'guru/' di URL dan 'guru.' di nama rute karena sudah diwakili oleh grup di atasnya
        Route::get('/profile', [ProfileController::class, 'guruShow'])->name('profile');
        Route::put('/profile-update', [ProfileController::class, 'guruUpdate'])->name('profile.update');
        Route::put('/password-update', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Kelola Kategori — hanya index, store, update, destroy (create/edit pakai modal, bukan halaman terpisah)
        Route::resource('kategori', CategoryController::class)
            ->except(['create', 'edit', 'show'])
            ->names('kategori')
            ->parameters(['kategori' => 'category']);

        // Data Siswa — guru mendaftarkan & mengelola akun siswa (create/edit pakai modal)
        Route::resource('siswa', StudentController::class)
            ->except(['create', 'edit', 'show'])
            ->names('siswa')
            ->parameters(['siswa' => 'siswa']);

        // withTrashed() wajib ada di kedua rute ini agar Laravel tetap bisa
        // menemukan akun siswa yang statusnya sudah soft-deleted (arsip).
        Route::put('siswa/{siswa}/restore', [StudentController::class, 'restore'])
            ->name('siswa.restore')
            ->withTrashed();
        Route::delete('siswa/{siswa}/force', [StudentController::class, 'forceDelete'])
            ->name('siswa.force-delete')
            ->withTrashed();
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

                // Prestasi & Sertifikat
        Route::get('/achievement',               [AchievementController::class, 'index'])->name('achievement.index');
        Route::post('/achievement',               [AchievementController::class, 'store'])->name('achievement.store');
        Route::get('/achievement/{achievement}/edit', [AchievementController::class, 'edit'])->name('achievement.edit');
        Route::put('/achievement/{achievement}',      [AchievementController::class, 'update'])->name('achievement.update');
        Route::delete('/achievement/{achievement}',   [AchievementController::class, 'destroy'])->name('achievement.destroy');

        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    });
        
    
});