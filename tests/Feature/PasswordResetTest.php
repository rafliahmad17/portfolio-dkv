<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
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

    public function test_reset_password_is_rejected_when_token_is_invalid(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'reset.invalid.token.test@example.test',
        ]);

        // Password lama (hash) sebelum request, untuk dibandingkan nanti.
        $oldPasswordHash = $user->password;

        // Sengaja tidak memakai Password::broker('users')->createToken($user)
        // -- token di bawah ini bukan token yang pernah dibuat/disimpan oleh
        // broker, sehingga harus ditolak sebagai token invalid.
        $response = $this->post(route('password.update'), [
            'token'                 => 'token-tidak-valid-'.str()->random(20),
            'email'                 => $user->email,
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        // ResetPasswordController::store() memetakan status selain
        // Password::PASSWORD_RESET (di sini: Password::INVALID_TOKEN) ke
        // field 'email' lewat ValidationException.
        $response->assertSessionHasErrors([
            'email' => __(Password::INVALID_TOKEN),
        ]);

        $user->refresh();

        // Password user sama sekali tidak berubah.
        $this->assertSame($oldPasswordHash, $user->password);

        // Password lama tetap valid.
        $this->assertTrue(Hash::check('password', $user->password));

        // Password baru yang dikirim tidak pernah menjadi password user.
        $this->assertFalse(Hash::check('passwordBaru123', $user->password));

        // Tidak ada sesi login yang terbentuk dari request yang ditolak ini.
        $this->assertGuest();
    }

    public function test_reset_password_is_rejected_when_password_is_too_short(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'reset.short.password.test@example.test',
        ]);

        $oldPasswordHash = $user->password;

        // Token valid, dibuat lewat broker "users" yang sama seperti test lain.
        $token = Password::broker('users')->createToken($user);

        $response = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'short1', // 6 karakter, di bawah min:8
            'password_confirmation' => 'short1',
        ]);

        // Rule 'min:8' pada field 'password' di ResetPasswordController::store()
        // ditolak oleh $request->validate() sebelum Password::reset() dipanggil.
        $response->assertSessionHasErrors('password');

        $user->refresh();

        // Password user tidak berubah karena request ditolak validasi.
        $this->assertSame($oldPasswordHash, $user->password);

        // Password lama tetap valid.
        $this->assertTrue(Hash::check('password', $user->password));

        // Password pendek yang dikirim tidak pernah menjadi password user.
        $this->assertFalse(Hash::check('short1', $user->password));

        $this->assertGuest();
    }

    public function test_reset_password_is_rejected_when_password_confirmation_does_not_match(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'reset.mismatch.confirmation.test@example.test',
        ]);

        $oldPasswordHash = $user->password;

        // Token valid, dibuat lewat broker "users" yang sama seperti test lain.
        $token = Password::broker('users')->createToken($user);

        $response = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBerbeda123',
        ]);

        // Rule 'confirmed' pada field 'password' di ResetPasswordController::store()
        // menaruh error di field 'password' (bukan 'password_confirmation') ketika
        // kedua nilai tidak cocok, dan ditolak sebelum Password::reset() dipanggil.
        $response->assertSessionHasErrors('password');

        $user->refresh();

        // Password user tidak berubah karena request ditolak validasi.
        $this->assertSame($oldPasswordHash, $user->password);

        // Password lama tetap valid.
        $this->assertTrue(Hash::check('password', $user->password));

        // Password baru yang dikirim tidak pernah menjadi password user.
        $this->assertFalse(Hash::check('passwordBaru123', $user->password));

        $this->assertGuest();
    }

    public function test_forgot_password_sends_reset_link_notification_for_registered_email(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'forgot.password.test@example.test',
        ]);

        // Set referer ke halaman forgot-password supaya redirect back() dari
        // ForgotPasswordController::store() bisa diverifikasi tujuannya.
        $response = $this->from(route('password.request'))->post(route('password.email'), [
            'email' => $user->email,
        ]);

        // ForgotPasswordController::store(): status Password::RESET_LINK_SENT
        // -> back()->with('status', __($status)), tanpa validation error.
        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('status', __(Password::RESET_LINK_SENT));
        $response->assertSessionHasNoErrors();

        // Password broker default (config/auth.php, broker "users") memanggil
        // $user->sendPasswordResetNotification($token). App\Models\User tidak
        // meng-override method tersebut, sehingga notifikasi yang benar-benar
        // dikirim adalah Illuminate\Auth\Notifications\ResetPassword bawaan
        // Laravel (lewat trait Illuminate\Auth\Passwords\CanResetPassword).
        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function (ResetPassword $notification) {
                return ! empty($notification->token);
            }
        );
    }

    public function test_forgot_password_is_rejected_for_unregistered_email(): void
    {
        Notification::fake();

        // Email ini sengaja tidak pernah dibuat lewat factory, supaya
        // Password::sendResetLink() tidak menemukan user manapun di
        // provider "users" (config/auth.php).
        $unregisteredEmail = 'tidak.terdaftar.test@example.test';

        $response = $this->post(route('password.email'), [
            'email' => $unregisteredEmail,
        ]);

        // Behavior aktual ForgotPasswordController::store(): saat
        // Password::sendResetLink() tidak menemukan user, broker
        // mengembalikan Password::INVALID_USER, dan controller
        // memetakan status tersebut ke field 'email' lewat
        // ValidationException (bukan redirect back() dengan status sukses).
        $response->assertSessionHasErrors([
            'email' => __(Password::INVALID_USER),
        ]);

        // Tidak ada notification reset password yang terkirim ke siapapun,
        // karena tidak ada user yang cocok dengan email tersebut.
        Notification::assertNothingSent();

        $this->assertGuest();
    }
}
