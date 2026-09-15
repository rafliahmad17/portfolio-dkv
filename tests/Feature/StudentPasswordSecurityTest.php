<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * FASE 7.4 — Student Password Security.
 *
 * Audit menemukan bahwa ProfileController::update() (form profil siswa)
 * sebelumnya mengizinkan penggantian password tanpa memverifikasi
 * password saat ini -- berbeda dari ProfileController::updatePassword()
 * milik guru yang sudah memverifikasi dengan Hash::check(). Hardening
 * pada Fase 7.4 menambahkan field & validasi 'current_password' pada
 * update profil siswa, mengikuti pola yang sama.
 *
 * Test ini membuktikan behavior yang sudah dihardening: password hanya
 * berubah jika current_password benar, password lama tidak pernah
 * disimpan sebagai plaintext, dan update profil non-password (tanpa
 * mengisi field password) tetap berfungsi seperti sebelumnya.
 */
class StudentPasswordSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_change_password_with_correct_current_password(): void
    {
        $siswa = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Uji',
        ]);

        $response = $this->actingAs($siswa)->put(route('siswa.profile.update'), [
            'name'                  => 'Siswa Uji',
            'current_password'      => 'password',
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertRedirect(route('siswa.profile.edit'));
        $response->assertSessionHasNoErrors();

        $siswa->refresh();
        $this->assertTrue(Hash::check('passwordBaru123', $siswa->password));
    }

    public function test_student_password_change_is_rejected_when_current_password_is_wrong(): void
    {
        $siswa = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Uji',
        ]);

        $response = $this->actingAs($siswa)->put(route('siswa.profile.update'), [
            'name'                  => 'Siswa Uji',
            'current_password'      => 'password-yang-salah',
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertSessionHasErrors('current_password');

        $siswa->refresh();

        // Password lama tetap tidak berubah.
        $this->assertTrue(Hash::check('password', $siswa->password));
        $this->assertFalse(Hash::check('passwordBaru123', $siswa->password));
    }

    public function test_student_password_change_requires_current_password_field(): void
    {
        $siswa = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Uji',
        ]);

        // current_password sama sekali tidak dikirim, padahal password
        // baru diisi -- harus ditolak oleh rule required_with:password.
        $response = $this->actingAs($siswa)->put(route('siswa.profile.update'), [
            'name'                  => 'Siswa Uji',
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertSessionHasErrors('current_password');

        $siswa->refresh();
        $this->assertTrue(Hash::check('password', $siswa->password));
    }

    public function test_student_new_password_is_hashed_and_old_password_stops_working(): void
    {
        $siswa = User::factory()->create([
            'role'  => 'siswa',
            'name'  => 'Siswa Uji',
            'email' => 'siswa.password.test@example.test',
        ]);

        $response = $this->actingAs($siswa)->put(route('siswa.profile.update'), [
            'name'                  => 'Siswa Uji',
            'current_password'      => 'password',
            'password'              => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertRedirect(route('siswa.profile.edit'));

        $siswa->refresh();

        // Tidak pernah tersimpan sebagai plaintext.
        $this->assertNotSame('passwordBaru123', $siswa->password);
        $this->assertTrue(Hash::check('passwordBaru123', $siswa->password));

        // Password lama tidak lagi bisa dipakai untuk login.
        $this->assertFalse(Hash::check('password', $siswa->password));

        // actingAs() di atas membuat sesi tetap "authenticated" untuk semua
        // request berikutnya. Tanpa logout eksplisit di sini, POST /login
        // akan langsung di-redirect oleh middleware 'guest' sebelum sempat
        // divalidasi, sehingga session tidak pernah berisi 'errors'.
        $this->post('/logout');

        $loginWithOldPassword = $this->post('/login', [
            'email'    => 'siswa.password.test@example.test',
            'password' => 'password',
        ]);
        $loginWithOldPassword->assertSessionHasErrors('email');
        $this->assertGuest();

        $loginWithNewPassword = $this->post('/login', [
            'email'    => 'siswa.password.test@example.test',
            'password' => 'passwordBaru123',
        ]);
        $loginWithNewPassword->assertRedirect(route('siswa.dashboard'));
        $this->assertAuthenticatedAs($siswa);
    }

    public function test_student_can_update_profile_without_changing_password(): void
    {
        $siswa = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Nama Lama',
        ]);

        $originalPasswordHash = $siswa->password;

        $response = $this->actingAs($siswa)->put(route('siswa.profile.update'), [
            'name' => 'Nama Baru',
            // Password fields sengaja dikosongkan -- tidak wajib diisi
            // saat siswa hanya ingin memperbarui data profil lain.
        ]);

        $response->assertRedirect(route('siswa.profile.edit'));
        $response->assertSessionHasNoErrors();

        $siswa->refresh();
        $this->assertSame('Nama Baru', $siswa->name);
        $this->assertSame($originalPasswordHash, $siswa->password);
    }
}
