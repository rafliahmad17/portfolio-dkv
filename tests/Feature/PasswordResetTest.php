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

    public function test_get_reset_password_page_shows_form_with_correct_token_and_email(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'reset.get.page.test@example.test',
        ]);

        // Token valid, dibuat lewat broker "users" yang sama seperti test lain
        // -- bukan token karangan sendiri.
        $token = Password::broker('users')->createToken($user);

        // Route GET /reset-password/{token} (name: password.reset) hanya
        // mendefinisikan {token} sebagai URI parameter (routes/web.php), jadi
        // 'email' otomatis ditambahkan sebagai query string oleh route()
        // -- sama seperti link yang dikirim lewat notifikasi reset password.
        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));

        $response->assertOk();
        $response->assertViewIs('auth.reset-password');

        // ResetPasswordController::create() mengirim $token (dari URI) dan
        // $email (dari query string, lewat $request->query('email')) sebagai
        // data view -- verifikasi langsung nilai yang diterima view.
        $response->assertViewHas('token', $token);
        $response->assertViewHas('email', $user->email);

        // View auth/reset-password.blade.php menaruh token di hidden input
        // <input type="hidden" name="token" value="{{ $token }}"> dan email
        // di <input type="email" id="email" name="email"
        // value="{{ old('email', $email) }}"> -- verifikasi kedua nilai
        // benar-benar dirender ke HTML, bukan cuma ada di view data.
        $response->assertSee('name="token"', false);
        $response->assertSee($token);
        $response->assertSee('id="email"', false);
        $response->assertSee($user->email);
    }

    public function test_reset_token_cannot_be_reused_after_successful_reset(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'reset.reused.token.test@example.test',
        ]);

        $token = Password::broker('users')->createToken($user);

        $firstResponse = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'passwordPertama123',
            'password_confirmation' => 'passwordPertama123',
        ]);

        $firstResponse->assertRedirect(route('login'));
        $firstResponse->assertSessionHasNoErrors();

        $user->refresh();
        $passwordAfterFirstReset = $user->password;
        $this->assertTrue(Hash::check('passwordPertama123', $passwordAfterFirstReset));

        // DatabaseTokenRepository::delete() (dipanggil PasswordBroker::reset()
        // setelah sukses) menghapus token dari database -- token yang sama
        // tidak boleh bisa dipakai untuk reset kedua kalinya.
        $secondResponse = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'passwordKedua456',
            'password_confirmation' => 'passwordKedua456',
        ]);

        $secondResponse->assertSessionHasErrors([
            'email' => __(Password::INVALID_TOKEN),
        ]);

        $user->refresh();

        // Password dari reset pertama tetap berlaku, tidak berubah oleh
        // percobaan reset kedua yang ditolak.
        $this->assertSame($passwordAfterFirstReset, $user->password);
        $this->assertTrue(Hash::check('passwordPertama123', $user->password));
        $this->assertFalse(Hash::check('passwordKedua456', $user->password));
    }

    public function test_authenticated_user_is_redirected_away_from_forgot_password_page(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        // Route GET /forgot-password ada di dalam Route::middleware('guest')
        // (routes/web.php) -- user yang sudah login tidak boleh mengakses
        // halaman ini. Middleware 'guest' bawaan Laravel tidak menemukan
        // route bernama 'dashboard'/'home' di project ini, sehingga redirect
        // default-nya jatuh ke '/'.
        $response = $this->actingAs($siswa)->get(route('password.request'));

        $response->assertRedirect('/');
    }

    public function test_authenticated_user_is_redirected_away_when_submitting_forgot_password_form(): void
    {
        Notification::fake();

        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->post(route('password.email'), [
            'email' => $siswa->email,
        ]);

        $response->assertRedirect('/');

        // Request diblokir middleware 'guest' sebelum mencapai
        // ForgotPasswordController::store(), jadi tidak ada notifikasi yang
        // terkirim sama sekali.
        Notification::assertNothingSent();
    }

    public function test_authenticated_user_is_redirected_away_from_reset_password_page(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);
        $token = Password::broker('users')->createToken($siswa);

        $response = $this->actingAs($siswa)->get(route('password.reset', [
            'token' => $token,
            'email' => $siswa->email,
        ]));

        $response->assertRedirect('/');
    }

    public function test_authenticated_user_is_redirected_away_when_submitting_reset_password_form(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);
        $token = Password::broker('users')->createToken($siswa);
        $originalPasswordHash = $siswa->password;

        $response = $this->actingAs($siswa)->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $siswa->email,
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertRedirect('/');

        $siswa->refresh();

        // Request diblokir middleware 'guest' sebelum mencapai
        // ResetPasswordController::store(), jadi password tidak pernah
        // berubah.
        $this->assertSame($originalPasswordHash, $siswa->password);
    }

    public function test_expired_reset_token_is_rejected(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'reset.expired.token.test@example.test',
        ]);

        $oldPasswordHash = $user->password;

        $token = Password::broker('users')->createToken($user);

        // config/auth.php: passwords.users.expire = 60 (menit). Majukan
        // waktu memakai time travel bawaan Laravel (Carbon::setTestNow(),
        // otomatis direset setelah test ini selesai) -- bukan sleep()
        // ataupun bergantung pada waktu nyata.
        $this->travel(61)->minutes();

        $response = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertSessionHasErrors([
            'email' => __(Password::INVALID_TOKEN),
        ]);

        $user->refresh();

        $this->assertSame($oldPasswordHash, $user->password);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertFalse(Hash::check('passwordBaru123', $user->password));
    }

    public function test_forgot_password_request_is_throttled_when_reset_token_was_recently_created(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'reset.broker.throttle.test@example.test',
        ]);

        // Token pertama dibuat langsung lewat broker (meniru link reset yang
        // baru saja dikirim). config/auth.php: passwords.users.throttle = 60
        // (detik) -- permintaan berikutnya untuk email yang sama masih ada
        // di dalam window tersebut tanpa perlu menunggu waktu nyata.
        Password::broker('users')->createToken($user);

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHasErrors([
            'email' => __(Password::RESET_THROTTLED),
        ]);

        Notification::assertNothingSent();
    }

    public function test_forgot_password_post_is_throttled_after_five_attempts_within_a_minute(): void
    {
        Notification::fake();

        // Email sengaja tidak terdaftar supaya tidak menyentuh throttle
        // broker (Password::INVALID_USER dikembalikan sebelum broker
        // memeriksa recentlyCreatedToken()) -- test ini murni membuktikan
        // throttle:5,1 pada route POST /forgot-password (routes/web.php).
        $unregisteredEmail = 'reset.route.throttle.test@example.test';

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->post(route('password.email'), [
                'email' => $unregisteredEmail,
            ]);

            $response->assertStatus(302);
        }

        $response = $this->post(route('password.email'), [
            'email' => $unregisteredEmail,
        ]);

        $response->assertStatus(429);

        Notification::assertNothingSent();
    }

    public function test_reset_password_post_is_throttled_after_five_attempts_within_a_minute(): void
    {
        // Token & email sengaja tidak valid -- test ini murni membuktikan
        // throttle:5,1 pada route POST /reset-password (routes/web.php),
        // bukan behavior Password::reset() itu sendiri (sudah dicakup test
        // lain di file ini).
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->post(route('password.update'), [
                'token'                 => 'token-tidak-valid',
                'email'                 => 'tidak.terdaftar.throttle.test@example.test',
                'password'              => 'passwordBaru123',
                'password_confirmation' => 'passwordBaru123',
            ]);

            $response->assertStatus(302);
        }

        $response = $this->post(route('password.update'), [
            'token'                 => 'token-tidak-valid',
            'email'                 => 'tidak.terdaftar.throttle.test@example.test',
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertStatus(429);
    }
}
