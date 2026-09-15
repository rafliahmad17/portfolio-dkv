<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 7.3 — Upload & CRUD Tests (Bagian C: Category CRUD).
 *
 * Membuktikan behavior CRUD CategoryController yang SUDAH ADA di
 * production bekerja dengan benar: create, validasi (required & unique),
 * update, dan delete — termasuk proteksi delete kategori yang masih
 * dipakai portfolio (behavior existing, TIDAK diubah). Akses lintas-role
 * ke halaman ini sudah dibuktikan di RoleAuthorizationTest (Fase 7.2) dan
 * TIDAK diulang di sini.
 */
class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_teacher_can_create_category_with_valid_data(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->post(route('guru.kategori.store'), [
            'name' => 'Fotografi Produk',
        ]);

        $response->assertRedirect(route('guru.kategori.index'));

        $this->assertDatabaseHas('categories', ['name' => 'Fotografi Produk']);
    }

    public function test_category_store_validation_rejects_missing_name_and_duplicate_name(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $missingNameResponse = $this->actingAs($guru)->post(route('guru.kategori.store'), []);
        $missingNameResponse->assertSessionHasErrors('name');

        Category::create(['name' => 'Ilustrasi Digital', 'slug' => 'ilustrasi-digital']);

        $duplicateResponse = $this->actingAs($guru)->post(route('guru.kategori.store'), [
            'name' => 'Ilustrasi Digital',
        ]);
        $duplicateResponse->assertSessionHasErrors('name');

        $this->assertSame(1, Category::where('name', 'Ilustrasi Digital')->count());
    }

    public function test_authorized_teacher_can_update_a_category(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $category = Category::create(['name' => 'Layout', 'slug' => 'layout']);

        $response = $this->actingAs($guru)->put(route('guru.kategori.update', $category), [
            'name' => 'Layouting Majalah',
        ]);

        $response->assertRedirect(route('guru.kategori.index'));

        $category->refresh();
        $this->assertSame('Layouting Majalah', $category->name);
    }

    public function test_category_that_is_not_used_by_any_portfolio_can_be_deleted(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $category = Category::create(['name' => 'Videografi', 'slug' => 'videografi']);

        $response = $this->actingAs($guru)->delete(route('guru.kategori.destroy', $category));

        $response->assertRedirect(route('guru.kategori.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_that_is_still_used_by_a_portfolio_cannot_be_deleted(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $student = User::factory()->create(['role' => 'siswa']);
        $category = Category::create(['name' => 'Branding', 'slug' => 'branding']);

        Portfolio::create([
            'title'       => 'Karya Branding',
            'slug'        => Portfolio::generateUniqueSlug('Karya Branding'),
            'description' => 'Deskripsi.',
            'image_path'  => 'portfolios/images/dummy.jpg',
            'user_id'     => $student->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($guru)->delete(route('guru.kategori.destroy', $category));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
