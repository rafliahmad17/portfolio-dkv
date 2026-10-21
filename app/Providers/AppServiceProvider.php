<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configureResetPasswordMail();
    }

    /**
     * Pembatas percobaan untuk login & reset password.
     *
     * Sebelumnya memakai throttle:5,1 yang membatasi per IP saja. Di jaringan
     * sekolah dengan satu IP publik, satu orang yang salah password 5 kali
     * mengunci SEMUA siswa selama semenit. Sekarang batas utama dihitung per
     * kombinasi email + IP, sehingga kesalahan satu akun tidak menghalangi akun
     * lain. Batas kedua per IP sengaja longgar -- hanya sebagai pengaman dari
     * serangan massal ke banyak email.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', fn (Request $request) => [
            Limit::perMinute(5)->by('login|' . $this->emailAndIpKey($request)),
            Limit::perMinute(100)->by('login-ip|' . $request->ip()),
        ]);

        RateLimiter::for('password-email', fn (Request $request) => [
            Limit::perMinute(5)->by('password-email|' . $this->emailAndIpKey($request)),
            Limit::perMinute(60)->by('password-email-ip|' . $request->ip()),
        ]);

        RateLimiter::for('password-reset', fn (Request $request) => [
            Limit::perMinute(5)->by('password-reset|' . $this->emailAndIpKey($request)),
            Limit::perMinute(60)->by('password-reset-ip|' . $request->ip()),
        ]);
    }

    private function emailAndIpKey(Request $request): string
    {
        $email = $request->input('email');
        $email = is_string($email) ? Str::lower(trim($email)) : '';

        return Str::transliterate($email . '|' . $request->ip());
    }

    /**
     * Email reset password berbahasa Indonesia (bawaan Laravel berbahasa Inggris).
     */
    private function configureResetPasswordMail(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)
                ->subject('Reset Password - DKV SMEKDA')
                ->greeting('Halo, ' . ($notifiable->name ?? 'Pengguna') . '!')
                ->line('Kami menerima permintaan untuk mereset password akun Portofolio DKV SMEKDA Anda.')
                ->action('Reset Password', $url)
                ->line("Tautan ini berlaku selama {$minutes} menit.")
                ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini. Password Anda tidak akan berubah.')
                ->salutation('Salam, Tim DKV SMEKDA');
        });
    }
}
