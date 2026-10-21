<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 7.2 — Security & Authorization Tests.
 *
 * Membuktikan rate limiting pada POST /login sesuai throttle:5,1 yang
 * sudah didefinisikan di routes/web.php (5 percobaan per menit, per
 * kombinasi IP+route bawaan Laravel). Tidak bergantung pada waktu nyata —
 * seluruh percobaan dikirim langsung berurutan di dalam satu test method,
 * jadi tidak butuh sleep()/travel() dan tidak berpotensi flaky.
 */
class LoginThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_sixth_login_attempt_within_a_minute_is_throttled(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'siswa@example.test',
        ]);

        // 5 percobaan pertama (sengaja pakai password salah) harus tetap
        // diproses normal oleh AuthController::login() — redirect back
        // dengan validation error — bukan diblokir oleh throttle.
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->post('/login', [
                'email'    => $user->email,
                'password' => 'password-salah',
            ]);

            $response->assertStatus(302);
            $response->assertSessionHasErrors('email');
        }

        // Percobaan ke-6 harus diblokir oleh middleware throttle:5,1
        // sebelum sempat masuk ke AuthController::login().
        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password-salah',
        ]);

        $response->assertStatus(429);
    }

    /**
     * TAHAP 1 poin 7: batas dihitung per kombinasi email + IP, bukan per IP.
     * Di jaringan sekolah dengan satu IP publik, salah password berulang pada
     * satu akun tidak boleh mengunci siswa lain yang login dengan benar.
     */
    public function test_throttled_account_does_not_block_other_accounts_from_the_same_ip(): void
    {
        $victimless = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'siswa.salah@example.test',
        ]);
        $other = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'siswa.lain@example.test',
        ]);

        // Akun pertama dikunci: 5 percobaan gagal, percobaan ke-6 mendapat 429.
        for ($i = 1; $i <= 5; $i++) {
            $this->post('/login', [
                'email'    => $victimless->email,
                'password' => 'password-salah',
            ])->assertStatus(302);
        }

        $this->post('/login', [
            'email'    => $victimless->email,
            'password' => 'password-salah',
        ])->assertStatus(429);

        // Akun lain dari IP yang sama tetap bisa login dengan password benar
        // (password bawaan UserFactory: "password").
        $response = $this->post('/login', [
            'email'    => $other->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('siswa.dashboard'));
        $this->assertAuthenticatedAs($other);
    }
}
