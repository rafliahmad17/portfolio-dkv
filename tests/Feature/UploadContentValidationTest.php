<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * FASE 7.3 — Upload & CRUD Tests (Bagian E: Upload Content Validation).
 *
 * Membuktikan bahwa ValidatesFileContent (dipakai bersama oleh
 * PortfolioController & AchievementController) benar-benar memeriksa ISI
 * file yang sesungguhnya lewat finfo, bukan sekadar ekstensi nama file.
 *
 * CATATAN TEKNIS:
 * Saat testing, Illuminate\Http\Testing\File (hasil dari UploadedFile::
 * fake()) meng-override getMimeType() agar menebak MIME HANYA dari
 * ekstensi nama file yang diberikan (lihat vendor/laravel/framework/src/
 * Illuminate/Http/Testing/File.php & MimeType.php). Akibatnya, rule
 * bawaan Laravel 'image'/'mimes:...' (yang bergantung pada
 * guessExtension() -> getMimeType()) TIDAK akan mendeteksi file palsu di
 * lingkungan test, karena rule tersebut akan selalu "percaya" ekstensi
 * nama filenya. Custom trait ValidatesFileContent TIDAK terpengaruh oleh
 * override ini karena ia membaca byte asli file secara langsung lewat
 * finfo->file($file->getRealPath()).
 *
 * Karena itu, test di bawah ini secara spesifik membuktikan lapisan
 * proteksi milik ValidatesFileContent itu sendiri: file bernama .jpg/.pdf
 * tetapi isinya sungguh-sungguh BUKAN gambar/PDF harus tetap ditolak, dan
 * request tidak boleh menghasilkan data baru di database. Skenario
 * sebaliknya (konten asli yang memang valid diterima) sudah dibuktikan
 * lewat test create yang berhasil di PortfolioCrudTest & AchievementCrudTest.
 */
class UploadContentValidationTest extends TestCase
{
    use RefreshDatabase;

    private function genuinePngBytes(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
        );
    }

    private function makeCategory(): Category
    {
        return Category::create(['name' => 'Poster', 'slug' => 'poster']);
    }

    public function test_portfolio_store_rejects_an_image_field_whose_real_content_is_not_an_image_despite_the_jpg_filename(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);
        $category = $this->makeCategory();

        $response = $this->actingAs($student)->post(route('siswa.portfolio.store'), [
            'title'       => 'Karya Upaya Penipuan Ekstensi',
            'category_id' => $category->id,
            'description' => 'Deskripsi.',
            'image'       => UploadedFile::fake()->createWithContent(
                'malicious.jpg',
                'ini hanya teks biasa, bukan file gambar sungguhan'
            ),
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('portfolios', ['title' => 'Karya Upaya Penipuan Ekstensi']);
    }

    public function test_portfolio_store_rejects_a_file_pdf_field_whose_real_content_is_not_a_pdf_despite_the_pdf_filename(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);
        $category = $this->makeCategory();

        $response = $this->actingAs($student)->post(route('siswa.portfolio.store'), [
            'title'       => 'Karya Lampiran Palsu',
            'category_id' => $category->id,
            'description' => 'Deskripsi.',
            'image'       => UploadedFile::fake()->createWithContent('karya.png', $this->genuinePngBytes()),
            'file_pdf'    => UploadedFile::fake()->createWithContent(
                'lampiran.pdf',
                'ini hanya teks biasa, bukan dokumen PDF sungguhan'
            ),
        ]);

        $response->assertSessionHasErrors('file_pdf');
        $this->assertDatabaseMissing('portfolios', ['title' => 'Karya Lampiran Palsu']);
    }

    public function test_achievement_store_rejects_an_image_field_whose_real_content_is_not_an_image_despite_the_jpg_filename(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->post(route('siswa.achievement.store'), [
            'type'  => 'sertifikat',
            'title' => 'Sertifikat Upaya Penipuan Ekstensi',
            'image' => UploadedFile::fake()->createWithContent(
                'badge.jpg',
                'ini hanya teks biasa, bukan file gambar sungguhan'
            ),
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('achievements', ['title' => 'Sertifikat Upaya Penipuan Ekstensi']);
    }
}
