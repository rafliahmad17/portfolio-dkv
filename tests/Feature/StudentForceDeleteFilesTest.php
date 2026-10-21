<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * TAHAP 2 -- Hapus permanen siswa tidak boleh meninggalkan file yatim.
 *
 * cascadeOnDelete() di database hanya menghapus BARIS portfolios/achievements.
 * File fisik (foto profil, gambar & PDF karya, gambar & dokumen prestasi) harus
 * dibuang oleh StudentController::forceDelete().
 */
class StudentForceDeleteFilesTest extends TestCase
{
    use RefreshDatabase;

    private function makeStudentWithFiles(string $prefix): array
    {
        Storage::disk('public')->put("{$prefix}/photo.jpg", 'x');
        Storage::disk('public')->put("{$prefix}/karya.jpg", 'x');
        Storage::disk('public')->put("{$prefix}/karya.pdf", 'x');
        Storage::disk('public')->put("{$prefix}/prestasi.jpg", 'x');
        Storage::disk('public')->put("{$prefix}/sertifikat.pdf", 'x');

        $siswa = User::factory()->create([
            'role'  => 'siswa',
            'photo' => "{$prefix}/photo.jpg",
        ]);

        $category = Category::firstOrCreate(['slug' => 'poster'], ['name' => 'Poster']);

        $karya = Portfolio::create([
            'title'         => "Karya {$prefix}",
            'slug'          => Portfolio::generateUniqueSlug("Karya {$prefix}"),
            'description'   => 'Deskripsi uji.',
            'image_path'    => "{$prefix}/karya.jpg",
            'file_pdf_path' => "{$prefix}/karya.pdf",
            'user_id'       => $siswa->id,
            'category_id'   => $category->id,
        ]);

        $prestasi = Achievement::create([
            'user_id'    => $siswa->id,
            'type'       => 'prestasi',
            'title'      => "Prestasi {$prefix}",
            'image_path' => "{$prefix}/prestasi.jpg",
            'file_path'  => "{$prefix}/sertifikat.pdf",
        ]);

        return [$siswa, $karya, $prestasi];
    }

    private function allPaths(string $prefix): array
    {
        return [
            "{$prefix}/photo.jpg",
            "{$prefix}/karya.jpg",
            "{$prefix}/karya.pdf",
            "{$prefix}/prestasi.jpg",
            "{$prefix}/sertifikat.pdf",
        ];
    }

    public function test_force_delete_removes_database_rows_and_all_storage_files(): void
    {
        Storage::fake('public');

        $guru = User::factory()->create(['role' => 'guru']);
        [$siswa, $karya, $prestasi] = $this->makeStudentWithFiles('siswa-a');

        $siswa->delete(); // arsipkan dulu (syarat forceDelete)

        $this->actingAs($guru)
            ->delete(route('guru.siswa.force-delete', $siswa))
            ->assertRedirect(route('guru.siswa.index', ['trashed' => 1]));

        // Baris database hilang (cascade).
        $this->assertDatabaseMissing('users', ['id' => $siswa->id]);
        $this->assertDatabaseMissing('portfolios', ['id' => $karya->id]);
        $this->assertDatabaseMissing('achievements', ['id' => $prestasi->id]);

        // Semua file fisik ikut hilang.
        foreach ($this->allPaths('siswa-a') as $path) {
            Storage::disk('public')->assertMissing($path);
        }
    }

    public function test_force_delete_does_not_touch_other_students_files(): void
    {
        Storage::fake('public');

        $guru = User::factory()->create(['role' => 'guru']);
        [$siswa]  = $this->makeStudentWithFiles('siswa-a');
        [$lain]   = $this->makeStudentWithFiles('siswa-b');

        $siswa->delete();

        $this->actingAs($guru)
            ->delete(route('guru.siswa.force-delete', $siswa))
            ->assertRedirect();

        foreach ($this->allPaths('siswa-b') as $path) {
            Storage::disk('public')->assertExists($path);
        }
        $this->assertDatabaseHas('users', ['id' => $lain->id]);
    }

    public function test_force_delete_is_rejected_and_keeps_files_when_student_is_not_archived(): void
    {
        Storage::fake('public');

        $guru = User::factory()->create(['role' => 'guru']);
        [$siswa] = $this->makeStudentWithFiles('siswa-a');

        // Belum diarsipkan -> harus ditolak (403), data & file utuh.
        $this->actingAs($guru)
            ->delete(route('guru.siswa.force-delete', $siswa))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $siswa->id]);
        foreach ($this->allPaths('siswa-a') as $path) {
            Storage::disk('public')->assertExists($path);
        }
    }

    public function test_force_delete_skips_shared_seeder_dummy_paths(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('portfolios/dummy/1.jpg', 'x');

        $guru  = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);
        $category = Category::create(['name' => 'Poster', 'slug' => 'poster']);

        Portfolio::create([
            'title'       => 'Karya Dummy',
            'slug'        => Portfolio::generateUniqueSlug('Karya Dummy'),
            'description' => 'Deskripsi uji.',
            'image_path'  => 'portfolios/dummy/1.jpg',
            'user_id'     => $siswa->id,
            'category_id' => $category->id,
        ]);

        $siswa->delete();

        $this->actingAs($guru)
            ->delete(route('guru.siswa.force-delete', $siswa))
            ->assertRedirect();

        // File contoh seeder dipakai bersama banyak akun demo -> tidak dihapus.
        Storage::disk('public')->assertExists('portfolios/dummy/1.jpg');
    }
}
