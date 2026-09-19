<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * FASE 9.4 — Guru Profile & Change Password Testing (#8).
 *
 * Membuktikan behavior ProfileController yang sudah ada di production untuk
 * guru: halaman profile bisa dibuka (guruShow(), route: guru.profile),
 * biodata (nama/NIP/email) bisa diperbarui (guruUpdate(), route:
 * guru.profile.update), dan password bisa diganti lewat endpoint terpisah
 * (updatePassword(), route: guru.profile.password) yang selalu mewajibkan
 * current_password + confirmed -- berbeda dari form profil siswa yang
 * menggabungkan biodata+password dalam satu route.
 *
 * Mengikuti pola StudentPasswordSecurityTest.php (current_password harus
 * benar, field wajib ada, password baru ter-hash dan password lama
 * berhenti berfungsi). Tidak mengubah production code.
 */
class GuruProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_view_profile_page(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('guru.profile'));

        $response->assertOk();
        $response->assertViewIs('guru.profile');
    }

    public function test_teacher_can_update_profile_biodata(): void
    {
        $guru = User::factory()->create([
            'role'  => 'guru',
            'name'  => 'Guru Lama',
            'email' => 'guru.profile.biodata.test@example.test',
        ]);

        $response = $this->actingAs($guru)->put(route('guru.profile.update'), [
            'name'    => 'Guru Baru',
            'nis_nip' => '198501012010011001',
            'email'   => $guru->email,
        ]);

        $response->assertRedirect(route('guru.profile'));
        $response->assertSessionHasNoErrors();

        $guru->refresh();
        $this->assertSame('Guru Baru', $guru->name);
        $this->assertSame('198501012010011001', $guru->nis_nip);
    }

    public function test_teacher_can_change_password_with_correct_current_password(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->put(route('guru.profile.password'), [
            'current_password'      => 'password',
            'password'              => 'passwordBaruGuru123',
            'password_confirmation' => 'passwordBaruGuru123',
        ]);

        $response->assertRedirect(route('guru.profile'));
        $response->assertSessionHasNoErrors();

        $guru->refresh();
        $this->assertTrue(Hash::check('passwordBaruGuru123', $guru->password));
    }

    public function test_teacher_password_change_is_rejected_when_current_password_is_wrong(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->put(route('guru.profile.password'), [
            'current_password'      => 'password-yang-salah',
            'password'              => 'passwordBaruGuru123',
            'password_confirmation' => 'passwordBaruGuru123',
        ]);

        $response->assertSessionHasErrors('current_password');

        $guru->refresh();

        // Password lama tetap tidak berubah.
        $this->assertTrue(Hash::check('password', $guru->password));
        $this->assertFalse(Hash::check('passwordBaruGuru123', $guru->password));
    }

    public function test_teacher_password_change_requires_current_password_field(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        // current_password sama sekali tidak dikirim -- ditolak oleh rule
        // 'required' pada ProfileController::updatePassword() (berbeda dari
        // form siswa yang memakai required_with:password, karena route guru
        // ini memang khusus untuk ganti password saja).
        $response = $this->actingAs($guru)->put(route('guru.profile.password'), [
            'password'              => 'passwordBaruGuru123',
            'password_confirmation' => 'passwordBaruGuru123',
        ]);

        $response->assertSessionHasErrors('current_password');

        $guru->refresh();
        $this->assertTrue(Hash::check('password', $guru->password));
    }

    public function test_teacher_new_password_is_hashed_and_old_password_stops_working(): void
    {
        $guru = User::factory()->create([
            'role'  => 'guru',
            'email' => 'guru.password.change.test@example.test',
        ]);

        $response = $this->actingAs($guru)->put(route('guru.profile.password'), [
            'current_password'      => 'password',
            'password'              => 'passwordBaruGuru123',
            'password_confirmation' => 'passwordBaruGuru123',
        ]);

        $response->assertRedirect(route('guru.profile'));

        $guru->refresh();

        // Tidak pernah tersimpan sebagai plaintext.
        $this->assertNotSame('passwordBaruGuru123', $guru->password);
        $this->assertTrue(Hash::check('passwordBaruGuru123', $guru->password));

        // Password lama tidak lagi bisa dipakai untuk login.
        $this->assertFalse(Hash::check('password', $guru->password));

        // actingAs() di atas membuat sesi tetap "authenticated". Tanpa logout
        // eksplisit, POST /login akan langsung di-redirect oleh middleware
        // 'guest' sebelum sempat divalidasi -- pola yang sama dipakai
        // StudentPasswordSecurityTest.php.
        $this->post('/logout');

        $loginWithOldPassword = $this->post('/login', [
            'email'    => 'guru.password.change.test@example.test',
            'password' => 'password',
        ]);
        $loginWithOldPassword->assertSessionHasErrors('email');
        $this->assertGuest();

        $loginWithNewPassword = $this->post('/login', [
            'email'    => 'guru.password.change.test@example.test',
            'password' => 'passwordBaruGuru123',
        ]);
        $loginWithNewPassword->assertRedirect(route('guru.dashboard'));
        $this->assertAuthenticatedAs($guru);
    }

    public function test_student_cannot_access_teacher_profile_page(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('guru.profile'));

        $response->assertForbidden();
    }
}
