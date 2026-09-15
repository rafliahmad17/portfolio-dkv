<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 7.2 — Security & Authorization Tests.
 *
 * Membuktikan ownership check di PortfolioController (abort_if 403) benar
 * mencegah satu siswa mengubah/menghapus portfolio milik siswa lain (IDOR).
 * Tidak mengubah authorization logic production — hanya membuktikan yang
 * sudah ada.
 */
class PortfolioOwnershipTest extends TestCase
{
    use RefreshDatabase;

    private function makePortfolioFor(User $owner): Portfolio
    {
        $category = Category::create(['name' => 'Poster', 'slug' => 'poster']);

        return Portfolio::create([
            'title'       => 'Karya Milik Owner',
            'slug'        => Portfolio::generateUniqueSlug('Karya Milik Owner'),
            'description' => 'Deskripsi karya.',
            'image_path'  => 'portfolios/images/dummy.jpg',
            'user_id'     => $owner->id,
            'category_id' => $category->id,
        ]);
    }

    public function test_student_cannot_update_another_students_portfolio(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $attacker = User::factory()->create(['role' => 'siswa']);
        $portfolio = $this->makePortfolioFor($owner);

        $response = $this->actingAs($attacker)->put(
            route('siswa.portfolio.update', $portfolio),
            [
                'title'       => 'Judul Diubah Paksa',
                'category_id' => $portfolio->category_id,
                'description' => 'Diubah paksa oleh siswa lain.',
            ]
        );

        $response->assertForbidden();
        $this->assertSame('Karya Milik Owner', $portfolio->fresh()->title);
    }

    public function test_student_cannot_delete_another_students_portfolio(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $attacker = User::factory()->create(['role' => 'siswa']);
        $portfolio = $this->makePortfolioFor($owner);

        $response = $this->actingAs($attacker)->delete(
            route('siswa.portfolio.destroy', $portfolio)
        );

        $response->assertForbidden();
        $this->assertModelExists($portfolio);
    }

    /**
     * Kontrol positif: membuktikan bahwa test IDOR di atas benar-benar
     * menguji ownership check, bukan sekadar route yang selalu menolak
     * semua request PUT/DELETE.
     */
    public function test_student_can_still_update_their_own_portfolio(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $portfolio = $this->makePortfolioFor($owner);

        $response = $this->actingAs($owner)->put(
            route('siswa.portfolio.update', $portfolio),
            [
                'title'       => 'Judul Baru',
                'category_id' => $portfolio->category_id,
                'description' => 'Deskripsi baru.',
            ]
        );

        $response->assertRedirect(route('siswa.dashboard'));
        $this->assertSame('Judul Baru', $portfolio->fresh()->title);
    }
}
