<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TAHAP 2 poin 8 -- Statistik dashboard siswa tidak boleh bergantung pada
 * slug kategori yang ditulis tetap di kode.
 *
 * Bug asal: DashboardController::siswa() mencari kategori lewat slug tetap
 * ('poster-banner', 'ui-ux-design'). Setelah guru mengganti nama kategori,
 * slug ikut berubah dan hitungan menjadi 0 padahal karya masih ada.
 */
class DashboardCategoryStatsTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(string $name): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => Category::generateUniqueSlug($name),
        ]);
    }

    private function makeWork(User $owner, Category $category, string $title): Portfolio
    {
        return Portfolio::create([
            'title'       => $title,
            'slug'        => Portfolio::generateUniqueSlug($title),
            'description' => 'Deskripsi karya untuk pengujian statistik.',
            'image_path'  => 'portfolios/images/dummy.jpg',
            'user_id'     => $owner->id,
            'category_id' => $category->id,
        ]);
    }

    public function test_category_count_survives_a_category_rename(): void
    {
        $guru  = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);

        $category = $this->makeCategory('Poster & Banner');
        $this->makeWork($siswa, $category, 'Poster Uji Rename');

        // Guru mengganti nama kategori lewat rute asli -> slug ikut berubah.
        $this->actingAs($guru)
            ->put(route('guru.kategori.update', $category), ['name' => 'Poster dan Banner'])
            ->assertRedirect(route('guru.kategori.index'));

        $this->assertSame('poster-dan-banner', $category->fresh()->slug);

        // Sebelum perbaikan: hitungan ini 0. Sesudah perbaikan: 1, dengan nama baru.
        $response = $this->actingAs($siswa)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertViewHas('kategoriTeratas', function (array $stat) {
            return $stat[0]['name'] === 'Poster dan Banner'
                && $stat[0]['total'] === 1;
        });
        $response->assertSee('Karya Poster dan Banner');
    }

    public function test_dashboard_shows_the_two_most_used_categories_in_order(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $a = $this->makeCategory('Kategori Tiga Karya');
        $b = $this->makeCategory('Kategori Dua Karya');
        $c = $this->makeCategory('Kategori Satu Karya');

        foreach (['A1', 'A2', 'A3'] as $t) { $this->makeWork($siswa, $a, "Karya $t"); }
        foreach (['B1', 'B2'] as $t)       { $this->makeWork($siswa, $b, "Karya $t"); }
        $this->makeWork($siswa, $c, 'Karya C1');

        $response = $this->actingAs($siswa)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalKarya', 6);
        $response->assertViewHas('kategoriTeratas', function (array $stat) {
            return count($stat) === 2
                && $stat[0] === ['name' => 'Kategori Tiga Karya', 'total' => 3]
                && $stat[1] === ['name' => 'Kategori Dua Karya', 'total' => 2];
        });
    }

    public function test_dashboard_only_counts_the_students_own_works(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);
        $lain  = User::factory()->create(['role' => 'siswa']);

        $category = $this->makeCategory('Ilustrasi Uji');
        $this->makeWork($siswa, $category, 'Karya Milik Siswa');
        $this->makeWork($lain, $category, 'Karya Milik Orang Lain');
        $this->makeWork($lain, $category, 'Karya Lain Kedua');

        $response = $this->actingAs($siswa)->get(route('siswa.dashboard'));

        $response->assertViewHas('kategoriTeratas', function (array $stat) {
            return $stat[0]['name'] === 'Ilustrasi Uji' && $stat[0]['total'] === 1;
        });
    }

    public function test_dashboard_without_works_shows_two_empty_slots(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertViewHas('kategoriTeratas', [
            ['name' => null, 'total' => 0],
            ['name' => null, 'total' => 0],
        ]);
        $response->assertSee('Kategori Lainnya');
    }
}
