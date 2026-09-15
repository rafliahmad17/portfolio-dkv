<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * FASE 7.3 — Upload & CRUD Tests (Bagian A: Portfolio CRUD).
 *
 * Membuktikan behavior CRUD utama PortfolioController (create, update,
 * delete) yang SUDAH ADA di production bekerja dengan benar, termasuk
 * penyimpanan/pergantian/penghapusan file upload (image & file_pdf) di
 * disk 'public'. Ownership check (abort_if 403) sudah dibuktikan di
 * PortfolioOwnershipTest (Fase 7.2) dan TIDAK diulang di sini, kecuali
 * sebagai setup minimal yang memang diperlukan untuk skenario update/
 * delete milik sendiri di bawah (yang sebelumnya belum ada test
 * positifnya untuk delete).
 */
class PortfolioCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PNG 1x1 piksel yang benar-benar valid (bukan sekadar diklaim lewat
     * nama file), supaya lolos baik validasi 'image'/'mimes' bawaan
     * Laravel maupun pengecekan konten asli (finfo) di ValidatesFileContent.
     */
    private function genuinePngBytes(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
        );
    }

    /**
     * PDF minimal yang diawali magic bytes "%PDF-" asli, supaya dikenali
     * finfo sebagai application/pdf (bukan cuma nama file .pdf).
     */
    private function genuinePdfBytes(): string
    {
        return "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
    }

    private function makeCategory(): Category
    {
        return Category::create(['name' => 'Poster', 'slug' => 'poster']);
    }

    public function test_authenticated_student_can_create_portfolio_with_valid_data_image_and_optional_pdf(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);
        $category = $this->makeCategory();

        $response = $this->actingAs($student)->post(route('siswa.portfolio.store'), [
            'title'       => 'Karya Poster Kampanye',
            'category_id' => $category->id,
            'description' => 'Deskripsi karya poster kampanye.',
            'image'       => UploadedFile::fake()->createWithContent('karya.png', $this->genuinePngBytes()),
            'file_pdf'    => UploadedFile::fake()->createWithContent('lampiran.pdf', $this->genuinePdfBytes()),
        ]);

        $response->assertRedirect(route('siswa.dashboard'));

        $this->assertDatabaseHas('portfolios', [
            'title'       => 'Karya Poster Kampanye',
            'category_id' => $category->id,
            'user_id'     => $student->id,
        ]);

        $portfolio = Portfolio::where('title', 'Karya Poster Kampanye')->firstOrFail();

        Storage::disk('public')->assertExists($portfolio->image_path);
        Storage::disk('public')->assertExists($portfolio->file_pdf_path);
    }

    public function test_portfolio_store_validation_rejects_missing_required_fields(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->post(route('siswa.portfolio.store'), []);

        $response->assertSessionHasErrors(['title', 'category_id', 'description', 'image']);
        $this->assertDatabaseCount('portfolios', 0);
    }

    public function test_portfolio_store_validation_rejects_a_category_id_that_does_not_exist(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->post(route('siswa.portfolio.store'), [
            'title'       => 'Karya Tanpa Kategori Valid',
            'category_id' => 99999,
            'description' => 'Deskripsi.',
            'image'       => UploadedFile::fake()->createWithContent('karya.png', $this->genuinePngBytes()),
        ]);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('portfolios', ['title' => 'Karya Tanpa Kategori Valid']);
    }

    public function test_student_can_update_their_own_portfolio_and_old_image_is_replaced(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);
        $category = $this->makeCategory();

        $oldImagePath = 'portfolios/images/lama.png';
        Storage::disk('public')->put($oldImagePath, $this->genuinePngBytes());

        $portfolio = Portfolio::create([
            'title'       => 'Karya Lama',
            'slug'        => Portfolio::generateUniqueSlug('Karya Lama'),
            'description' => 'Deskripsi lama.',
            'image_path'  => $oldImagePath,
            'user_id'     => $student->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($student)->put(route('siswa.portfolio.update', $portfolio), [
            'title'       => 'Karya Diperbarui',
            'category_id' => $category->id,
            'description' => 'Deskripsi baru.',
            'image'       => UploadedFile::fake()->createWithContent('baru.png', $this->genuinePngBytes()),
        ]);

        $response->assertRedirect(route('siswa.dashboard'));

        $portfolio->refresh();

        $this->assertSame('Karya Diperbarui', $portfolio->title);
        $this->assertNotSame($oldImagePath, $portfolio->image_path);

        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($portfolio->image_path);
    }

    public function test_student_can_delete_their_own_portfolio_and_its_files_are_removed_from_storage(): void
    {
        Storage::fake('public');

        $student = User::factory()->create(['role' => 'siswa']);
        $category = $this->makeCategory();

        $imagePath = 'portfolios/images/dihapus.png';
        $pdfPath = 'portfolios/pdf/dihapus.pdf';
        Storage::disk('public')->put($imagePath, $this->genuinePngBytes());
        Storage::disk('public')->put($pdfPath, $this->genuinePdfBytes());

        $portfolio = Portfolio::create([
            'title'         => 'Karya Untuk Dihapus',
            'slug'          => Portfolio::generateUniqueSlug('Karya Untuk Dihapus'),
            'description'   => 'Deskripsi.',
            'image_path'    => $imagePath,
            'file_pdf_path' => $pdfPath,
            'user_id'       => $student->id,
            'category_id'   => $category->id,
        ]);

        $response = $this->actingAs($student)->delete(route('siswa.portfolio.destroy', $portfolio));

        $response->assertRedirect(route('siswa.dashboard'));

        $this->assertDatabaseMissing('portfolios', ['id' => $portfolio->id]);
        Storage::disk('public')->assertMissing($imagePath);
        Storage::disk('public')->assertMissing($pdfPath);
    }
}
