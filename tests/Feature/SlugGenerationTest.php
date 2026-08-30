<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Test minimal untuk mekanisme pembuatan slug yang diperbaiki pada FASE 1:
 * - User::generateUniquePortfolioSlug()
 * - Portfolio::generateUniqueSlug()
 * - Category::generateUniqueSlug()
 *
 * Fokus: (1) slug yang dihasilkan TIDAK PERNAH memuat ID database, dan
 * (2) collision (nama/judul yang sama atau menghasilkan base slug yang
 * sama) tetap bisa disimpan berdampingan tanpa error.
 */
class SlugGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        // Kembalikan Str::random() ke perilaku normal supaya tidak bocor
        // ke test lain di luar file ini.
        Str::createRandomStringsNormally();

        parent::tearDown();
    }

    /**
     * Buat beberapa baris "pengisi" di setiap tabel sebelum baris yang
     * benar-benar diuji, supaya auto-increment ID baris yang diuji jauh
     * dari angka kecil (1, 2, 3, ...). Ini membuat assertion "slug tidak
     * memuat ID" jauh lebih meyakinkan: kalau kebetulan cocok dengan angka
     * kecil seperti "-2" atau "-3", itu bisa jadi kebetulan suffix
     * berurutan Category, bukan bukti nyata bahwa ID tidak terpakai. Dengan
     * ID yang sengaja dibuat besar, satu-satunya cara suffix bisa "cocok"
     * dengan ID adalah kalau memang kode-nya salah memakai ID.
     */
    private function padCategoryIdsPastSmallNumbers(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Category::create([
                'name' => "Kategori Pengisi {$i}",
                'slug' => "kategori-pengisi-{$i}",
            ]);
        }
    }

    private function padUserIdsPastSmallNumbers(): void
    {
        User::factory()->count(10)->create(['role' => 'guru']);
    }

    private function padPortfolioIdsPastSmallNumbers(User $owner): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Portfolio::create([
                'title'       => "Karya Pengisi {$i}",
                'slug'        => "karya-pengisi-{$i}",
                'description' => 'Pengisi.',
                'image_path'  => "portfolios/dummy/{$i}.jpg",
                'user_id'     => $owner->id,
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | User::generateUniquePortfolioSlug()
    |--------------------------------------------------------------------------
    */

    public function test_student_slug_never_contains_the_database_id(): void
    {
        $this->padUserIdsPastSmallNumbers();

        $user = User::factory()->create(['role' => 'siswa', 'name' => 'Budi Siswa Contoh']);

        $slug = User::generateUniquePortfolioSlug($user->name);

        // Fallback lama yang bocor adalah: Str::slug($name) . '-' . $user->id
        $leakyLegacyPattern = Str::slug($user->name) . '-' . $user->id;

        $this->assertNotSame($leakyLegacyPattern, $slug);
        $this->assertStringStartsWith(Str::slug($user->name) . '-', $slug);
        $this->assertStringNotContainsString((string) $user->id, $slug);
    }

    public function test_student_slug_collision_is_resolved_without_using_the_id(): void
    {
        $name = 'Budi Siswa Kembar';
        $base = Str::slug($name);

        // Paksa dua siswa dengan nama identik, siswa pertama sudah memakai
        // suffix acak "AAAAAA" (disimulasikan lewat createRandomStringsUsingSequence).
        Str::createRandomStringsUsingSequence(['AAAAAA']);
        $firstSlug = User::generateUniquePortfolioSlug($name);
        $firstUser = User::factory()->create(['role' => 'siswa', 'name' => $name, 'portfolio_slug' => $firstSlug]);

        $this->assertSame($base . '-AAAAAA', $firstSlug);

        // Siswa kedua dengan nama sama: percobaan pertama sengaja dibuat
        // bentrok (kembali "AAAAAA"), lalu percobaan kedua harus berhasil
        // dengan suffix berbeda ("BBBBBB") karena method mendeteksi slug
        // pertama sudah dipakai dan mengulang.
        Str::createRandomStringsUsingSequence(['AAAAAA', 'BBBBBB']);
        $secondSlug = User::generateUniquePortfolioSlug($name);

        $this->assertSame($base . '-BBBBBB', $secondSlug);
        $this->assertNotSame($firstSlug, $secondSlug);
        $this->assertStringNotContainsString((string) $firstUser->id, $secondSlug);
    }

    /*
    |--------------------------------------------------------------------------
    | Portfolio::generateUniqueSlug()
    |--------------------------------------------------------------------------
    */

    public function test_portfolio_slug_never_contains_the_database_id(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $this->padPortfolioIdsPastSmallNumbers($owner);

        $title = 'Karya Poster';
        $base = Str::slug($title);

        // Kendalikan suffix acak supaya assertion deterministik. Memeriksa
        // "apakah digit ID kebetulan muncul di dalam suffix acak" (versi
        // sebelumnya) bersifat probabilistik: Str::random(6) bisa saja
        // kebetulan menghasilkan karakter yang sama dengan ID (mis. owner
        // id = 1 dan suffix "v1Ccvo"), padahal itu bukan bukti generator
        // memakai ID. Dengan suffix yang ditentukan lewat
        // createRandomStringsUsingSequence(), kita bisa membuktikan secara
        // pasti bahwa slug = base + '-' + suffix acak tersebut, bukan
        // base + '-' + ID.
        Str::createRandomStringsUsingSequence(['AAAAAA']);
        $slug = Portfolio::generateUniqueSlug($title);

        $portfolio = Portfolio::create([
            'title'       => $title,
            'slug'        => $slug,
            'description' => 'Deskripsi karya.',
            'image_path'  => 'portfolios/images/dummy.jpg',
            'user_id'     => $owner->id,
        ]);

        // Bukti kuat: slug persis sama dengan suffix acak yang sudah
        // ditentukan, bukan sekadar "tidak mengandung digit ID".
        $this->assertSame($base . '-AAAAAA', $slug);

        // Pastikan slug juga bukan salah satu pola eksplisit yang dulu bocor
        // memakai ID (mis. base-{portfolioId} atau base-{ownerId}).
        $this->assertNotSame($base . '-' . $portfolio->id, $slug);
        $this->assertNotSame($base . '-' . $owner->id, $slug);
    }

    public function test_two_portfolios_with_identical_title_get_different_slugs(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $title = 'Karya Poster';

        // Dua karya dengan judul identik dari siswa yang sama harus tetap
        // bisa disimpan berdampingan (unique constraint di kolom slug tidak
        // boleh membuat insert kedua gagal / 500).
        $slugA = Portfolio::generateUniqueSlug($title);
        Portfolio::create([
            'title' => $title, 'slug' => $slugA, 'description' => 'A',
            'image_path' => 'portfolios/images/a.jpg', 'user_id' => $owner->id,
        ]);

        $slugB = Portfolio::generateUniqueSlug($title);
        Portfolio::create([
            'title' => $title, 'slug' => $slugB, 'description' => 'B',
            'image_path' => 'portfolios/images/b.jpg', 'user_id' => $owner->id,
        ]);

        $this->assertNotSame($slugA, $slugB);
        $this->assertSame(2, Portfolio::where('title', $title)->count());
    }

    public function test_portfolio_slug_collision_is_resolved_without_using_the_id(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $title = 'Karya Poster Kembar';
        $base = Str::slug($title);

        Str::createRandomStringsUsingSequence(['AAAAAA']);
        $firstSlug = Portfolio::generateUniqueSlug($title);
        $firstPortfolio = Portfolio::create([
            'title' => $title, 'slug' => $firstSlug, 'description' => 'A',
            'image_path' => 'portfolios/images/a.jpg', 'user_id' => $owner->id,
        ]);

        Str::createRandomStringsUsingSequence(['AAAAAA', 'BBBBBB']);
        $secondSlug = Portfolio::generateUniqueSlug($title);

        $this->assertSame($base . '-BBBBBB', $secondSlug);
        $this->assertNotSame($firstSlug, $secondSlug);
        $this->assertStringNotContainsString((string) $firstPortfolio->id, $secondSlug);
    }

    /*
    |--------------------------------------------------------------------------
    | Category::generateUniqueSlug()
    |--------------------------------------------------------------------------
    */

    public function test_category_slug_never_contains_the_database_id(): void
    {
        $this->padCategoryIdsPastSmallNumbers();

        $slug = Category::generateUniqueSlug('Fotografi');
        $category = Category::create(['name' => 'Fotografi', 'slug' => $slug]);

        $this->assertSame('fotografi', $slug);
        $this->assertStringNotContainsString((string) $category->id, $slug);
    }

    public function test_two_category_names_that_normalize_to_the_same_slug_do_not_collide(): void
    {
        $this->padCategoryIdsPastSmallNumbers();

        // "UI/UX" dan "UI UX" adalah NAMA yang berbeda (lolos validasi
        // unique:categories,name) tapi Str::slug() keduanya menghasilkan
        // "ui-ux" yang sama persis -> ini skenario collision yang harus
        // ditangani dengan baik (contoh dari task: ui-ux, ui-ux-2).
        $slugA = Category::generateUniqueSlug('UI/UX');
        $categoryA = Category::create(['name' => 'UI/UX', 'slug' => $slugA]);

        $slugB = Category::generateUniqueSlug('UI UX');
        $categoryB = Category::create(['name' => 'UI UX', 'slug' => $slugB]);

        $slugC = Category::generateUniqueSlug('UI  UX');
        $categoryC = Category::create(['name' => 'UI  UX', 'slug' => $slugC]);

        $this->assertSame('ui-ux', $slugA);
        $this->assertSame('ui-ux-2', $slugB);
        $this->assertSame('ui-ux-3', $slugC);

        // Suffix "-2" / "-3" adalah counter berurutan yang independen dari
        // ID database (yang sudah sengaja dibuat besar oleh padCategoryIdsPastSmallNumbers()).
        $this->assertGreaterThan(10, $categoryA->id);
        $this->assertGreaterThan(10, $categoryB->id);
        $this->assertGreaterThan(10, $categoryC->id);
    }

    public function test_category_update_ignores_its_own_slug_when_name_is_unchanged(): void
    {
        $category = Category::create(['name' => 'Ilustrasi Digital', 'slug' => 'ilustrasi-digital']);

        // Guru membuka form edit tapi tidak mengubah nama sama sekali.
        // Kategori tidak boleh dianggap "bentrok" dengan slug miliknya sendiri.
        $slug = Category::generateUniqueSlug('Ilustrasi Digital', $category->id);

        $this->assertSame('ilustrasi-digital', $slug);
    }

    public function test_category_slug_collision_does_not_throw_a_raw_database_error(): void
    {
        Category::create(['name' => 'Branding & Logo', 'slug' => 'branding-logo']);

        // Simulasikan alur CategoryController::store(): nama berbeda ("Branding & Logo"
        // vs "Branding   Logo" berspasi ganda) tapi Str::slug() keduanya sama-sama
        // menghasilkan "branding-logo" -> insert kedua tidak boleh melempar
        // QueryException/500.
        $collidingSlug = Category::generateUniqueSlug('Branding   Logo');
        $category = Category::create(['name' => 'Branding   Logo', 'slug' => $collidingSlug]);

        $this->assertSame('branding-logo-2', $collidingSlug);
        $this->assertNotNull($category->id);
    }
}
