<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 7.2 — Security & Authorization Tests.
 *
 * Membuktikan behavior login yang sudah ada di AuthController::login():
 * kredensial valid berhasil login + redirect sesuai role, kredensial salah
 * / email tidak terdaftar ditolak. Tidak mengubah redirect behavior yang
 * sudah ada.
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_login_with_valid_credentials_and_is_redirected_to_student_dashboard(): void
    {
        $user = User::factory()->create([
            'role'  => 'siswa',
            'email' => 'siswa@example.test',
        ]);

        $response = $this->post('/login', [
            'email'    => 'siswa@example.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('siswa.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_teacher_can_login_with_valid_credentials_and_is_redirected_to_teacher_dashboard(): void
    {
        $user = User::factory()->create([
            'role'  => 'guru',
            'email' => 'guru@example.test',
        ]);

        $response = $this->post('/login', [
            'email'    => 'guru@example.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('guru.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'role'  => 'siswa',
            'email' => 'siswa@example.test',
        ]);

        $response = $this->post('/login', [
            'email'    => 'siswa@example.test',
            'password' => 'password-salah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_fails_with_unregistered_email(): void
    {
        $response = $this->post('/login', [
            'email'    => 'tidak-terdaftar@example.test',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
