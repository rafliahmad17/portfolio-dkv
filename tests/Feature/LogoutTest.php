<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 9.3 — Logout Testing.
 *
 * Membuktikan behavior AuthController::logout() (route POST /logout,
 * middleware 'auth') yang sudah ada di production: user yang login bisa
 * logout dan sesinya benar-benar berakhir, halaman yang butuh auth tidak
 * bisa diakses lagi setelah logout, dan guest tidak bisa memanggil
 * endpoint logout (diblokir middleware 'auth' sebelum sempat masuk ke
 * controller). Tidak mengubah production code.
 */
class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_protected_route_is_no_longer_accessible_after_logout(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($user)->post(route('logout'));

        $response = $this->get(route('siswa.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_call_the_logout_endpoint(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
