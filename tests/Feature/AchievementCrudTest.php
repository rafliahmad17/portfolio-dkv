<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * FASE 7.3 — Upload & CRUD Tests (Bagian B: Achievement CRUD).
 *
 * Membuktikan behavior CRUD utama AchievementController (create, update,
 * delete) yang SUDAH ADA di production bekerja dengan benar, termasuk
 * penyimpanan/pergantian/penghapusan file upload (image & file sertifikat)
 * di disk 'public'. Ownership check (abort_unless 403) sudah dibuktikan di
 * AchievementOwnershipTest (Fase 7.2) dan TIDAK diulang di sini, kecuali
 * sebagai setup minimal untuk skenario delete milik sendiri yang memang
 * belum ada test positifnya sebelumnya.
 */
class AchievementCrudTest extends TestCase
{
    use RefreshDatabase;

    private function genuinePngBytes(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
        );
    }

    private function genuinePdfBytes(): string
    {
        return "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
    }

    public function test_authenticated_student_can_create_achievement_with_valid_data_and_optional_files(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->post(route('siswa.achievement.store'), [
            'type'        => 'sertifikat',
            'title'       => 'Juara 1 Lomba Poster Digital',
            'issuer'      => 'Dinas Pendidikan',
            'description' => 'Deskripsi prestasi.',
            'achieved_at' => '2026-03-10',
            'image'       => UploadedFile::fake()->createWithContent('badge.png', $this->genuinePngBytes()),
            'file'        => UploadedFile::fake()->createWithContent('sertifikat.pdf', $this->genuinePdfBytes()),
        ]);

        $response->assertRedirect(route('siswa.achievement.index'));

        $this->assertDatabaseHas('achievements', [
            'title'   => 'Juara 1 Lomba Poster Digital',
            'user_id' => $student->id,
            'type'    => 'sertifikat',
        ]);

        $achievement = Achievement::where('title', 'Juara 1 Lomba Poster Digital')->firstOrFail();

        Storage::disk('public')->assertExists($achievement->image_path);
        Storage::disk('public')->assertExists($achievement->file_path);
    }

    public function test_achievement_store_validation_rejects_invalid_type_and_missing_title(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->post(route('siswa.achievement.store'), [
            'type' => 'bukan-tipe-valid',
        ]);

        $response->assertSessionHasErrors(['type', 'title']);
        $this->assertDatabaseCount('achievements', 0);
    }

    public function test_student_can_update_their_own_achievement_and_old_files_are_replaced(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);

        $oldImagePath = 'achievements/photos/lama.png';
        Storage::disk('public')->put($oldImagePath, $this->genuinePngBytes());

        $achievement = Achievement::create([
            'user_id'    => $student->id,
            'type'       => 'sertifikat',
            'title'      => 'Sertifikat Lama',
            'image_path' => $oldImagePath,
        ]);

        $response = $this->actingAs($student)->put(route('siswa.achievement.update', $achievement), [
            'type'  => 'sertifikat',
            'title' => 'Sertifikat Diperbarui',
            'image' => UploadedFile::fake()->createWithContent('baru.png', $this->genuinePngBytes()),
        ]);

        $response->assertRedirect(route('siswa.achievement.index'));

        $achievement->refresh();

        $this->assertSame('Sertifikat Diperbarui', $achievement->title);
        $this->assertNotSame($oldImagePath, $achievement->image_path);

        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($achievement->image_path);
    }

    public function test_student_can_delete_their_own_achievement_and_its_files_are_removed_from_storage(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);

        $imagePath = 'achievements/photos/dihapus.png';
        $filePath = 'achievements/docs/dihapus.pdf';
        Storage::disk('public')->put($imagePath, $this->genuinePngBytes());
        Storage::disk('public')->put($filePath, $this->genuinePdfBytes());

        $achievement = Achievement::create([
            'user_id'    => $student->id,
            'type'       => 'sertifikat',
            'title'      => 'Sertifikat Untuk Dihapus',
            'image_path' => $imagePath,
            'file_path'  => $filePath,
        ]);

        $response = $this->actingAs($student)->delete(route('siswa.achievement.destroy', $achievement));

        $response->assertRedirect(route('siswa.achievement.index'));

        $this->assertDatabaseMissing('achievements', ['id' => $achievement->id]);
        Storage::disk('public')->assertMissing($imagePath);
        Storage::disk('public')->assertMissing($filePath);
    }
}
