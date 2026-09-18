<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/**
 * FASE 9.4 — Forgot Password & Reset Password Testing (HIGH #1).
 *
 * Membuktikan behavior ResetPasswordController::store() (route POST
 * /reset-password, name: password.update) yang sudah ada di production:
 * reset password dengan token valid benar-benar mengubah password user
 * di database (tersimpan sebagai hash, bukan plaintext), password lama
 * berhenti berfungsi, password baru bisa dipakai login, dan user
 * diarahkan kembali ke halaman login dengan status sukses di session.
 *
 * Token dibuat lewat Password::createToken() (broker "users", sesuai
 * config/auth.php) -- mekanisme resmi Laravel yang sama dipakai untuk
 * membuat token yang dikirim lewat notifikasi reset password, sehingga
 * kompatibel dengan Password::reset() yang dipanggil controller.
 *
 * Tidak mengubah production code (route/controller/model/config/migration).
 */
class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password_with_valid_token_and_login_with_new_password(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'reset.password.test@example.test',
        ]);

        // Password lama (hash) sebelum reset, untuk dibandingkan nanti.
        $oldPasswordHash = $user->password;
        $this->assertTrue(Hash::check('password', $oldPasswordHash));

        // Buat token reset lewat broker "users" yang sama dipakai
        // ForgotPasswordController/ResetPasswordController (config/auth.php),
        // bukan token karangan sendiri.
        $token = Password::broker('users')->createToken($user);

        $response = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        // Redirect ke login + status sukses di session (Password::PASSWORD_RESET).
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', __(Password::PASSWORD_RESET));
        $response->assertSessionHasNoErrors();

        $user->refresh();

        // Password baru tersimpan sebagai hash, bukan plaintext.
        $this->assertNotSame('passwordBaru123', $user->password);
        $this->assertTrue(Hash::check('passwordBaru123', $user->password));

        // Hash di database benar-benar berubah dari hash lama.
        $this->assertNotSame($oldPasswordHash, $user->password);

        // Password lama tidak lagi cocok dengan hash yang tersimpan.
        $this->assertFalse(Hash::check('password', $user->password));

        // Password lama tidak lagi bisa dipakai untuk login.
        $loginWithOldPassword = $this->post('/login', [
            'email'    => 'reset.password.test@example.test',
            'password' => 'password',
        ]);
        $loginWithOldPassword->assertSessionHasErrors('email');
        $this->assertGuest();

        // Password baru berhasil dipakai untuk login.
        $loginWithNewPassword = $this->post('/login', [
            'email'    => 'reset.password.test@example.test',
            'password' => 'passwordBaru123',
        ]);
        $loginWithNewPassword->assertRedirect(route('siswa.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
