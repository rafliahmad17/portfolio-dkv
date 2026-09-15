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
}
