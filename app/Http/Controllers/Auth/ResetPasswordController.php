<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public const SUCCESS_STATUS = 'Password berhasil direset. Silakan masuk dengan password baru Anda.';

    /**
     * Token salah, token kedaluwarsa, dan email tidak dikenal dijawab dengan
     * pesan yang sama supaya tidak membocorkan apakah sebuah email terdaftar.
     */
    public const FAILED_MESSAGE = 'Tautan reset password tidak valid atau sudah kedaluwarsa. '
        . 'Silakan minta tautan baru lewat halaman Lupa Password.';

    public function create(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'token.required'     => 'Token reset tidak ditemukan. Gunakan tautan dari email Anda.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Format alamat email tidak valid.',
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', self::SUCCESS_STATUS);
        }

        throw ValidationException::withMessages([
            'email' => [self::FAILED_MESSAGE],
        ]);
    }
}
