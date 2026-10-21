<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Pesan yang SAMA untuk email terdaftar maupun tidak, supaya orang luar
     * tidak bisa menebak email mana yang punya akun.
     */
    public const GENERIC_STATUS = 'Jika email tersebut terdaftar, tautan reset password telah dikirim. '
        . 'Silakan periksa kotak masuk dan folder spam Anda.';

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
        ]);

        try {
            // Status (terkirim / email tidak terdaftar / terlalu sering) sengaja
            // TIDAK ditampilkan ke pengguna -- semuanya dijawab dengan pesan yang sama.
            Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            // Kegagalan kirim email (SMTP salah, dll.) hanya terjadi untuk akun yang
            // ada, jadi jika dibiarkan menjadi error 500 ia juga membocorkan akun.
            // Catat di log untuk admin, tampilkan pesan yang sama ke pengguna.
            report($e);
        }

        return back()->with('status', self::GENERIC_STATUS);
    }
}
