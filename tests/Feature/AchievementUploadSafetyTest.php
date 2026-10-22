<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Unggahan yang isinya tidak valid TIDAK boleh menghapus file lama milik
 * prestasi/sertifikat. File lama baru dibuang setelah database menunjuk ke
 * file baru.
 */
class AchievementUploadSafetyTest extends TestCase
{
    use RefreshDatabase;

    private function pngBytes(): string
    {
        // PNG 1x1 yang valid.
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
        );
    }

    private function pdfBytes(): string
    {
        return "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
    }

    private function makeAchievement(User $siswa): Achievement
    {
        Storage::disk('public')->put('achievements/photos/lama.png', 'GAMBAR-LAMA');
        Storage::disk('public')->put('achievements/docs/lama.pdf', 'PDF-LAMA');

        return Achievement::create([
            'user_id'    => $siswa->id,
            'type'       => 'sertifikat',
            'title'      => 'Sertifikat Lama',
            'image_path' => 'achievements/photos/lama.png',
            'file_path'  => 'achievements/docs/lama.pdf',
        ]);
    }

    public function test_invalid_image_content_keeps_old_files_and_database_paths(): void
    {
        Storage::fake('public');
        $siswa = User::factory()->create(['role' => 'siswa']);
        $achievement = $this->makeAchievement($siswa);

        $this->actingAs($siswa)->put(route('siswa.achievement.update', $achievement), [
            'type'  => 'sertifikat',
            'title' => 'Judul Baru',
            'image' => UploadedFile::fake()->createWithContent('palsu.png', 'ini bukan gambar'),
        ])->assertSessionHasErrors('image');

        Storage::disk('public')->assertExists('achievements/photos/lama.png');
        Storage::disk('public')->assertExists('achievements/docs/lama.pdf');
        $this->assertSame('achievements/photos/lama.png', $achievement->fresh()->image_path);
        $this->assertSame('Sertifikat Lama', $achievement->fresh()->title);
    }

    public function test_invalid_pdf_content_keeps_old_files_even_when_image_is_valid(): void
    {
        Storage::fake('public');
        $siswa = User::factory()->create(['role' => 'siswa']);
        $achievement = $this->makeAchievement($siswa);

        $this->actingAs($siswa)->put(route('siswa.achievement.update', $achievement), [
            'type'  => 'sertifikat',
            'title' => 'Judul Baru',
            'image' => UploadedFile::fake()->createWithContent('baru.png', $this->pngBytes()),
            'file'  => UploadedFile::fake()->createWithContent('palsu.pdf', 'ini bukan pdf'),
        ])->assertSessionHasErrors('file');

        // Tidak ada yang berubah: gambar lama utuh, dan gambar baru tidak sempat tersimpan.
        Storage::disk('public')->assertExists('achievements/photos/lama.png');
        Storage::disk('public')->assertExists('achievements/docs/lama.pdf');
        $this->assertSame('achievements/photos/lama.png', $achievement->fresh()->image_path);
        $this->assertCount(1, Storage::disk('public')->files('achievements/photos'));
    }

    public function test_valid_replacement_deletes_old_files_after_saving(): void
    {
        Storage::fake('public');
        $siswa = User::factory()->create(['role' => 'siswa']);
        $achievement = $this->makeAchievement($siswa);

        $this->actingAs($siswa)->put(route('siswa.achievement.update', $achievement), [
            'type'  => 'sertifikat',
            'title' => 'Judul Baru',
            'image' => UploadedFile::fake()->createWithContent('baru.png', $this->pngBytes()),
            'file'  => UploadedFile::fake()->createWithContent('baru.pdf', $this->pdfBytes()),
        ])->assertSessionHasNoErrors();

        $fresh = $achievement->fresh();

        $this->assertNotSame('achievements/photos/lama.png', $fresh->image_path);
        $this->assertNotSame('achievements/docs/lama.pdf', $fresh->file_path);
        Storage::disk('public')->assertExists($fresh->image_path);
        Storage::disk('public')->assertExists($fresh->file_path);
        Storage::disk('public')->assertMissing('achievements/photos/lama.png');
        Storage::disk('public')->assertMissing('achievements/docs/lama.pdf');
    }
}
