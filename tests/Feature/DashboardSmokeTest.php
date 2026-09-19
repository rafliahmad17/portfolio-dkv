<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 9.4 — Dashboard Smoke Tests (#6, #7).
 *
 * Membuktikan DashboardController::siswa() dan DashboardController::guru()
 * benar-benar bisa diakses (HTTP 200) oleh user dengan role yang sesuai,
 * memakai route asli routes/web.php (name: siswa.dashboard, guru.dashboard).
 *
 * Skenario akses ditolak (guest diarahkan ke login, lintas-role diblokir
 * middleware 'role') sudah dicakup RoleAuthorizationTest, jadi tidak
 * diulang di sini -- file ini murni smoke test "halaman benar-benar bisa
 * dibuka" untuk role yang berhak.
 */
class DashboardSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_student_can_open_student_dashboard(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertViewIs('siswa.dashboard');
    }

    public function test_authenticated_teacher_can_open_teacher_dashboard(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('guru.dashboard'));

        $response->assertOk();
        $response->assertViewIs('guru.dashboard');
    }
}
