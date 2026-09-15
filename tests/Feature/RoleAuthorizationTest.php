<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 7.2 — Security & Authorization Tests.
 *
 * Membuktikan middleware 'role:...' dan 'auth' bawaan Laravel benar-benar
 * memblokir akses lintas-role dan akses tanpa login, memakai route asli
 * yang sudah ada di routes/web.php (tidak ada route testing baru).
 */
class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_access_teacher_dashboard(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('guru.dashboard'));

        $response->assertForbidden();
    }

    public function test_student_cannot_access_teacher_category_management(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('guru.kategori.index'));

        $response->assertForbidden();
    }

    public function test_teacher_cannot_access_student_dashboard(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('siswa.dashboard'));

        $response->assertForbidden();
    }

    public function test_teacher_cannot_access_student_portfolio_upload_form(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('siswa.portfolio.create'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_when_accessing_teacher_dashboard(): void
    {
        $response = $this->get(route('guru.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_to_login_when_accessing_student_dashboard(): void
    {
        $response = $this->get(route('siswa.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
