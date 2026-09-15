<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 7.3 — Upload & CRUD Tests (Bagian D: Student Management).
 *
 * Membuktikan behavior CRUD StudentController yang SUDAH ADA di
 * production bekerja dengan benar: melihat daftar siswa, membuat &
 * memperbarui akun siswa, validasi, serta siklus arsip (soft delete),
 * restore, dan force delete permanen (termasuk proteksi force delete pada
 * akun yang belum diarsipkan — behavior existing, TIDAK diubah). Akses
 * lintas-role ke halaman ini sudah dibuktikan di RoleAuthorizationTest
 * (Fase 7.2) dan TIDAK diulang di sini.
 */
class StudentManagementCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_teacher_can_view_student_management_index(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('guru.siswa.index'));

        $response->assertOk();
    }

    public function test_authorized_admin_can_view_student_management_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('guru.siswa.index'));

        $response->assertOk();
    }

    public function test_authorized_teacher_can_create_a_student_account_with_valid_data(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->post(route('guru.siswa.store'), [
            'name'     => 'Budi Siswa Baru',
            'email'    => 'budi.siswa.baru@example.test',
            'nis_nip'  => '2026001',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('guru.siswa.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'budi.siswa.baru@example.test',
            'role'  => 'siswa',
        ]);

        $siswa = User::where('email', 'budi.siswa.baru@example.test')->firstOrFail();
        $this->assertNotNull($siswa->portfolio_slug);
    }

    public function test_student_store_validation_rejects_missing_required_fields_and_duplicate_email(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $missingFieldsResponse = $this->actingAs($guru)->post(route('guru.siswa.store'), []);
        $missingFieldsResponse->assertSessionHasErrors(['name', 'email', 'password']);

        User::factory()->create(['role' => 'siswa', 'email' => 'siswa.lama@example.test']);

        $duplicateEmailResponse = $this->actingAs($guru)->post(route('guru.siswa.store'), [
            'name'     => 'Siswa Duplikat',
            'email'    => 'siswa.lama@example.test',
            'password' => 'password123',
        ]);
        $duplicateEmailResponse->assertSessionHasErrors('email');
    }

    public function test_authorized_teacher_can_update_student_data(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create([
            'role'  => 'siswa',
            'name'  => 'Nama Lama',
            'email' => 'nama.lama@example.test',
        ]);

        $response = $this->actingAs($guru)->put(route('guru.siswa.update', $siswa), [
            'name'  => 'Nama Baru',
            'email' => 'nama.baru@example.test',
        ]);

        $response->assertRedirect(route('guru.siswa.index'));

        $siswa->refresh();
        $this->assertSame('Nama Baru', $siswa->name);
        $this->assertSame('nama.baru@example.test', $siswa->email);
    }

    public function test_authorized_teacher_can_archive_a_student_account(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($guru)->delete(route('guru.siswa.destroy', $siswa));

        $response->assertRedirect(route('guru.siswa.index'));
        $this->assertSoftDeleted('users', ['id' => $siswa->id]);
    }

    public function test_authorized_teacher_can_restore_an_archived_student_account(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);
        $siswa->delete();

        $response = $this->actingAs($guru)->put(route('guru.siswa.restore', $siswa));

        $response->assertRedirect(route('guru.siswa.index'));

        $this->assertNotSoftDeleted('users', ['id' => $siswa->id]);
    }

    public function test_authorized_teacher_can_permanently_force_delete_an_archived_student_account(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);
        $siswa->delete();

        $response = $this->actingAs($guru)->delete(route('guru.siswa.force-delete', $siswa));

        $response->assertRedirect(route('guru.siswa.index', ['trashed' => 1]));

        $this->assertDatabaseMissing('users', ['id' => $siswa->id]);
    }

    public function test_force_delete_is_rejected_for_a_student_account_that_is_not_archived(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($guru)->delete(route('guru.siswa.force-delete', $siswa));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $siswa->id]);
    }
}
