<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

/**
 * TAHAP 2 poin 12 -- Daftar karya harus dipaginasi.
 *
 * Bug asal: dashboard siswa dan profil publik memuat SEMUA karya sekaligus
 * (->get()). Sekarang dipaginasi 12 per halaman, sementara angka ringkasan
 * (total karya, kategori, tagline) tetap dihitung dari seluruh karya.
 */
class PortfolioPaginationTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(string $name): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => Category::generateUniqueSlug($name),
        ]);
    }

    /**
     * Buat $count karya; karya ke-1 paling lama, karya ke-$count paling baru.
     *
     * @return array<int, Portfolio> indeks 1..$count
     */
    private function makeWorks(User $owner, Category $category, int $count): array
    {
        $works = [];

        for ($i = 1; $i <= $count; $i++) {
            $work = Portfolio::create([
                'title'       => "Karya Nomor {$i}",
                'slug'        => Portfolio::generateUniqueSlug("Karya Nomor {$i}"),
                'description' => 'Deskripsi karya untuk pengujian paginasi.',
                'image_path'  => 'portfolios/images/dummy.jpg',
                'user_id'     => $owner->id,
                'category_id' => $category->id,
            ]);

            // created_at dibuat berurutan agar urutan "terbaru" deterministik.
            $work->forceFill(['created_at' => now()->subHours($count - $i)])->saveQuietly();

            $works[$i] = $work;
        }

        return $works;
    }

    public function test_student_dashboard_paginates_works_and_keeps_the_newest_as_hero(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);
        $works = $this->makeWorks($siswa, $this->makeCategory('Poster Uji'), 15);

        $page1 = $this->actingAs($siswa)->get(route('siswa.dashboard'));

        $page1->assertOk();
        $page1->assertViewHas('totalKarya', 15);
        $page1->assertViewHas('featured', fn (Portfolio $f) => $f->id === $works[15]->id);
        $page1->assertViewHas('portfolios', function (LengthAwarePaginator $p) use ($works) {
            return $p->total() === 14                       // 15 karya - 1 hero
                && $p->count() === 12                       // halaman pertama penuh
                && $p->lastPage() === 2
                && ! $p->pluck('id')->contains($works[15]->id); // hero tidak ikut grid
        });
        $page1->assertSee('Halaman 1 dari 2');

        $page2 = $this->actingAs($siswa)->get(route('siswa.dashboard', ['page' => 2]));

        $page2->assertOk();
        // Hero tetap karya terbaru di halaman mana pun; sisanya 2 karya terlama.
        $page2->assertViewHas('featured', fn (Portfolio $f) => $f->id === $works[15]->id);
        $page2->assertViewHas('portfolios', function (LengthAwarePaginator $p) use ($works) {
            return $p->count() === 2
                && $p->pluck('id')->sort()->values()->all() === [$works[1]->id, $works[2]->id];
        });
    }

    public function test_student_dashboard_without_pagination_needs_no_page_links(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);
        $this->makeWorks($siswa, $this->makeCategory('Poster Uji'), 5);

        $response = $this->actingAs($siswa)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertViewHas('portfolios', fn (LengthAwarePaginator $p) => $p->count() === 4);
        $response->assertDontSee('Navigasi halaman karya');
    }

    public function test_student_dashboard_without_works_still_renders(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertViewHas('featured', null);
        $response->assertViewHas('totalKarya', 0);
        $response->assertSee('Belum ada karya di arsip ini.');
    }

    public function test_public_profile_paginates_but_summary_counts_cover_all_works(): void
    {
        $siswa = User::factory()->create([
            'role'           => 'siswa',
            'portfolio_slug' => 'siswa-paginasi',
        ]);

        $banyak  = $this->makeCategory('Kategori Banyak');
        $sedikit = $this->makeCategory('Kategori Sedikit');

        $this->makeWorks($siswa, $banyak, 13);
        $this->makeWorks($siswa, $sedikit, 1);

        $page1 = $this->get(route('portfolio.profile', 'siswa-paginasi'));

        $page1->assertOk();
        $page1->assertViewHas('totalKarya', 14);
        $page1->assertViewHas('totalKategori', 2);
        // Tagline memakai kategori terbanyak dari SEMUA karya, bukan hanya halaman ini.
        $page1->assertViewHas('kategoriDominan', 'Kategori Banyak');
        $page1->assertViewHas('portfolios', fn (LengthAwarePaginator $p) => $p->count() === 12 && $p->total() === 14);
        $page1->assertSee('Spesialis Kategori Banyak');

        $page2 = $this->get(route('portfolio.profile', ['slug' => 'siswa-paginasi', 'page' => 2]));

        $page2->assertOk();
        $page2->assertViewHas('portfolios', fn (LengthAwarePaginator $p) => $p->count() === 2);
        // Halaman 2 tidak boleh salah menampilkan "belum mengunggah karya".
        $page2->assertDontSee('Siswa ini belum mengunggah karya.');
    }

    public function test_public_profile_without_works_shows_empty_state(): void
    {
        User::factory()->create([
            'role'           => 'siswa',
            'portfolio_slug' => 'siswa-kosong',
        ]);

        $response = $this->get(route('portfolio.profile', 'siswa-kosong'));

        $response->assertOk();
        $response->assertViewHas('totalKarya', 0);
        $response->assertSee('Siswa ini belum mengunggah karya.');
    }
}
