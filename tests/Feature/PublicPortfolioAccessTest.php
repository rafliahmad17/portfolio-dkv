<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 9.2 — Public Portfolio & Archived Access Tests.
 *
 * Membuktikan behavior halaman publik PublicPortfolioController
 * (show/profile/print) untuk portofolio & profil siswa: akses normal
 * untuk siswa aktif, 404 untuk slug yang tidak ditemukan, dan 404 untuk
 * portofolio/profil milik siswa yang sudah diarsipkan (soft-deleted) --
 * meskipun Portfolio itu sendiri tidak ikut soft-delete saat siswa
 * pemiliknya diarsipkan.
 */
class PublicPortfolioAccessTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(): Category
    {
        return Category::create(['name' => 'Poster', 'slug' => 'poster']);
    }

    private function makePortfolioFor(User $owner, ?Category $category = null): Portfolio
    {
        $category ??= $this->makeCategory();

        return Portfolio::create([
            'title'       => 'Karya Publik Uji Coba',
            'slug'        => Portfolio::generateUniqueSlug('Karya Publik Uji Coba'),
            'description' => 'Deskripsi karya untuk pengujian halaman publik.',
            'image_path'  => 'portfolios/images/dummy.jpg',
            'user_id'     => $owner->id,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Set portfolio_slug secara langsung (bukan lewat array factory),
     * karena 'portfolio_slug' TIDAK ada di User::$fillable -- pola yang
     * sama persis dipakai StudentController & DashboardController di
     * production (assignment properti langsung, bukan mass assignment).
     */
    private function assignPortfolioSlug(User $user, string $slug): void
    {
        $user->portfolio_slug = $slug;
        $user->save();
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Public portfolio show -- slug valid
    |--------------------------------------------------------------------------
    */

    public function test_public_portfolio_show_renders_successfully_for_a_valid_slug(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Pemilik Karya',
        ]);
        // Samakan state $student dengan siswa production: siswa aktif
        // selalu punya portfolio_slug terisi (lihat StudentController /
        // DashboardController). UserFactory tidak mengisi kolom ini secara
        // default, jadi diisi eksplisit lewat helper yang sudah ada.
        $this->assignPortfolioSlug($student, 'siswa-pemilik-karya-uji');
        $portfolio = $this->makePortfolioFor($student);

        $response = $this->get(route('portfolio.public', $portfolio->slug));

        $response->assertOk();
        $response->assertSee($portfolio->title);
        $response->assertSee($student->name);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Public student profile -- slug valid
    |--------------------------------------------------------------------------
    */

    public function test_public_student_profile_renders_successfully_for_a_valid_slug(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Profil Publik',
            'bio'  => 'Siswa jurusan DKV.',
        ]);
        $this->assignPortfolioSlug($student, 'siswa-profil-publik-uji');

        $response = $this->get(route('portfolio.profile', $student->portfolio_slug));

        $response->assertOk();
        $response->assertSee($student->name);
    }

    /*
    |--------------------------------------------------------------------------
    | 3 & 4. Slug tidak ditemukan -- harus 404, bukan error lain
    |--------------------------------------------------------------------------
    */

    public function test_public_portfolio_show_returns_404_for_a_slug_that_does_not_exist(): void
    {
        $response = $this->get(route('portfolio.public', 'slug-yang-tidak-ada'));

        $response->assertNotFound();
    }

    public function test_public_student_profile_returns_404_for_a_slug_that_does_not_exist(): void
    {
        $response = $this->get(route('portfolio.profile', 'slug-yang-tidak-ada'));

        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | 5. ARCHIVED STUDENT -- portofolio via /p/{slug} (PRIORITAS UTAMA)
    |--------------------------------------------------------------------------
    */

    public function test_public_portfolio_detail_for_an_archived_students_portfolio_should_not_leak_a_server_error(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Diarsipkan',
        ]);
        $portfolio = $this->makePortfolioFor($student);

        // Simpan slug SEBELUM diarsipkan -- merepresentasikan link/QR lama
        // yang sudah pernah dibagikan siswa saat masih aktif.
        $slug = $portfolio->slug;

        // Arsipkan (soft delete) siswa pemiliknya. Portfolio TIDAK ikut
        // terhapus -- cascadeOnDelete() di migrasi hanya berlaku untuk
        // DELETE fisik, bukan soft delete (lihat komentar
        // StudentController::destroy()).
        $student->delete();

        $response = $this->get(route('portfolio.public', $slug));

        // Portofolio milik siswa yang sudah diarsipkan tidak boleh lagi
        // bisa diakses publik lewat link/QR lama -- harus 404.
        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | 6. ARCHIVED STUDENT -- profil via /u/{slug}
    |--------------------------------------------------------------------------
    */

    public function test_public_student_profile_for_an_archived_student_returns_404(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Profil Diarsipkan',
        ]);
        $this->assignPortfolioSlug($student, 'siswa-arsip-uji-coba');
        $slug = $student->portfolio_slug;

        $student->delete();

        $response = $this->get(route('portfolio.profile', $slug));

        // Query profile() dijalankan langsung pada model User, sehingga
        // global scope SoftDeletes otomatis menyaring siswa yang sudah
        // diarsipkan sebelum sampai ke Blade view -- hasilnya 404.
        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | 7. Bonus -- smoke test halaman cetak publik (/u/{slug}/print)
    |--------------------------------------------------------------------------
    | PublicPortfolioController::print() memakai pola query yang identik
    | dengan profile() (langsung ke model User + firstOrFail), dan hanya
    | me-render Blade view biasa (bukan generate PDF lewat library
    | eksternal), sehingga aman & murah untuk disertakan sebagai smoke test.
    */

    public function test_public_print_view_renders_successfully_for_a_valid_slug(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Cetak Publik',
        ]);
        $this->assignPortfolioSlug($student, 'siswa-cetak-uji-coba');
        $this->makePortfolioFor($student);

        $response = $this->get(route('portfolio.public.print', $student->portfolio_slug));

        $response->assertOk();
        $response->assertSee($student->name);
    }

    public function test_public_print_view_for_an_archived_student_returns_404(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Cetak Diarsipkan',
        ]);
        $this->assignPortfolioSlug($student, 'siswa-cetak-arsip-uji-coba');
        $slug = $student->portfolio_slug;

        $student->delete();

        $response = $this->get(route('portfolio.public.print', $slug));

        // Sama seperti profile(): query langsung ke model User dengan
        // firstOrFail(), sehingga global scope SoftDeletes menyaring siswa
        // yang sudah diarsipkan sebelum sampai ke view -- hasilnya 404.
        $response->assertNotFound();
    }
}
