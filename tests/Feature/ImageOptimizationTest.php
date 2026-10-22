<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use App\Services\ImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * TAHAP 4 poin 19 -- Optimasi gambar: thumbnail, WebP, dan dimensi.
 *
 * Bug asal: gambar karya (maks. 2 MB) disimpan apa adanya dan ditampilkan
 * penuh di setiap kartu, logo 430 KB dimuat di tiap halaman, dan tag <img>
 * tidak punya width/height sehingga layout bergeser saat gambar dimuat.
 */
class ImageOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (! ImageOptimizer::supported()) {
            $this->markTestSkipped('Ekstensi GD dengan dukungan WebP tidak tersedia.');
        }

        Storage::fake('public');
    }

    private function siswa(): User
    {
        return User::factory()->create(['role' => 'siswa']);
    }

    private function category(): Category
    {
        return Category::create(['name' => 'Poster', 'slug' => 'poster']);
    }

    private function upload(Category $category, UploadedFile $image, User $siswa, string $title = 'Karya Optimasi')
    {
        return $this->actingAs($siswa)->post(route('siswa.portfolio.store'), [
            'title'       => $title,
            'category_id' => $category->id,
            'description' => 'Deskripsi karya untuk uji optimasi gambar.',
            'image'       => $image,
        ]);
    }

    /** @return array{0:int,1:int} */
    private function storedSize(string $path): array
    {
        $info = getimagesizefromstring(Storage::disk('public')->get($path));

        return [$info[0], $info[1]];
    }

    // ------------------------------------------------------------------
    // Unggah karya
    // ------------------------------------------------------------------

    public function test_upload_stores_a_resized_webp_and_a_thumbnail_with_dimensions(): void
    {
        $siswa = $this->siswa();

        $this->upload($this->category(), UploadedFile::fake()->image('foto.jpg', 2400, 1600), $siswa)
            ->assertRedirect(route('siswa.dashboard'));

        $karya = Portfolio::firstOrFail();

        $this->assertStringStartsWith('portfolios/images/', $karya->image_path);
        $this->assertStringEndsWith('.webp', $karya->image_path);
        $this->assertStringStartsWith('portfolios/thumbs/', $karya->thumbnail_path);
        $this->assertStringEndsWith('.webp', $karya->thumbnail_path);

        Storage::disk('public')->assertExists($karya->image_path);
        Storage::disk('public')->assertExists($karya->thumbnail_path);

        // Gambar utama diperkecil ke sisi terpanjang 1600 px; thumbnail 480 px.
        $this->assertSame([1600, 1067], $this->storedSize($karya->image_path));
        $this->assertSame([480, 320], $this->storedSize($karya->thumbnail_path));

        // Dimensi gambar utama tersimpan di database.
        $this->assertSame(1600, $karya->image_width);
        $this->assertSame(1067, $karya->image_height);
    }

    public function test_small_images_are_never_upscaled(): void
    {
        $this->upload($this->category(), UploadedFile::fake()->image('kecil.png', 300, 200), $this->siswa());

        $karya = Portfolio::firstOrFail();

        $this->assertSame([300, 200], $this->storedSize($karya->image_path));
        $this->assertSame([300, 200], $this->storedSize($karya->thumbnail_path));
        $this->assertSame(300, $karya->image_width);
        $this->assertSame(200, $karya->image_height);
    }

    public function test_replacing_the_image_removes_the_old_image_and_old_thumbnail(): void
    {
        $siswa = $this->siswa();
        $category = $this->category();

        $this->upload($category, UploadedFile::fake()->image('lama.jpg', 800, 600), $siswa);
        $karya = Portfolio::firstOrFail();
        $oldImage = $karya->image_path;
        $oldThumb = $karya->thumbnail_path;

        $this->actingAs($siswa)->put(route('siswa.portfolio.update', $karya), [
            'title'       => 'Karya Optimasi',
            'category_id' => $category->id,
            'description' => 'Deskripsi karya untuk uji optimasi gambar.',
            'image'       => UploadedFile::fake()->image('baru.jpg', 1000, 500),
        ])->assertRedirect(route('siswa.dashboard'));

        $karya->refresh();

        $this->assertNotSame($oldImage, $karya->image_path);
        $this->assertNotSame($oldThumb, $karya->thumbnail_path);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertMissing($oldThumb);
        Storage::disk('public')->assertExists($karya->image_path);
        Storage::disk('public')->assertExists($karya->thumbnail_path);
        $this->assertSame(1000, $karya->image_width);
        $this->assertSame(500, $karya->image_height);
    }

    public function test_updating_without_a_new_image_keeps_image_thumbnail_and_dimensions(): void
    {
        $siswa = $this->siswa();
        $category = $this->category();

        $this->upload($category, UploadedFile::fake()->image('foto.jpg', 800, 600), $siswa);
        $karya = Portfolio::firstOrFail();
        $before = $karya->only(['image_path', 'thumbnail_path', 'image_width', 'image_height']);

        $this->actingAs($siswa)->put(route('siswa.portfolio.update', $karya), [
            'title'       => 'Judul Baru Saja',
            'category_id' => $category->id,
            'description' => 'Deskripsi baru.',
        ])->assertRedirect(route('siswa.dashboard'));

        $this->assertSame($before, $karya->fresh()->only(['image_path', 'thumbnail_path', 'image_width', 'image_height']));
        Storage::disk('public')->assertExists($before['image_path']);
        Storage::disk('public')->assertExists($before['thumbnail_path']);
    }

    public function test_deleting_a_portfolio_removes_image_and_thumbnail(): void
    {
        $siswa = $this->siswa();

        $this->upload($this->category(), UploadedFile::fake()->image('foto.jpg', 800, 600), $siswa);
        $karya = Portfolio::firstOrFail();

        $this->actingAs($siswa)->delete(route('siswa.portfolio.destroy', $karya))
            ->assertRedirect(route('siswa.dashboard'));

        Storage::disk('public')->assertMissing($karya->image_path);
        Storage::disk('public')->assertMissing($karya->thumbnail_path);
    }

    public function test_force_deleting_a_student_removes_thumbnails_too(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = $this->siswa();

        $this->upload($this->category(), UploadedFile::fake()->image('foto.jpg', 800, 600), $siswa);
        $karya = Portfolio::firstOrFail();

        $siswa->delete(); // arsipkan dulu (syarat hapus permanen)

        $this->actingAs($guru)
            ->delete(route('guru.siswa.force-delete', $siswa))
            ->assertRedirect();

        Storage::disk('public')->assertMissing($karya->image_path);
        Storage::disk('public')->assertMissing($karya->thumbnail_path);
    }

    // ------------------------------------------------------------------
    // Foto profil
    // ------------------------------------------------------------------

    public function test_profile_photo_is_resized_to_a_small_webp(): void
    {
        $siswa = $this->siswa();

        $this->actingAs($siswa)->put(route('siswa.profile.update'), [
            'name'  => $siswa->name,
            'photo' => UploadedFile::fake()->image('saya.jpg', 1200, 800),
        ])->assertRedirect(route('siswa.profile.edit'));

        $photo = $siswa->fresh()->photo;

        $this->assertStringStartsWith('profiles/photos/', $photo);
        $this->assertStringEndsWith('.webp', $photo);
        $this->assertSame([512, 341], $this->storedSize($photo));
    }

    // ------------------------------------------------------------------
    // Model & tampilan
    // ------------------------------------------------------------------

    public function test_model_helpers_expose_thumbnail_urls_and_size_attributes(): void
    {
        $karya = new Portfolio([
            'image_path'     => 'portfolios/images/a.webp',
            'thumbnail_path' => 'portfolios/thumbs/a.webp',
            'image_width'    => 1600,
            'image_height'   => 1067,
        ]);

        $this->assertStringEndsWith('storage/portfolios/images/a.webp', $karya->image_url);
        $this->assertStringEndsWith('storage/portfolios/thumbs/a.webp', $karya->thumbnail_url);
        $this->assertSame('width="1600" height="1067"', $karya->imageSizeAttributes());
        $this->assertSame('width="480" height="320"', $karya->imageSizeAttributes(true));
    }

    public function test_legacy_portfolio_without_thumbnail_or_dimensions_still_works(): void
    {
        $karya = new Portfolio(['image_path' => 'portfolios/images/lama.jpg']);

        // Thumbnail jatuh kembali ke gambar utama; tanpa dimensi -> atribut kosong.
        $this->assertStringEndsWith('storage/portfolios/images/lama.jpg', $karya->thumbnail_url);
        $this->assertSame('', $karya->imageSizeAttributes());
        $this->assertSame('', $karya->imageSizeAttributes(true));
    }

    public function test_dashboards_use_thumbnails_with_dimensions_for_cards(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'portfolio_slug' => 'siswa-thumb']);
        $category = $this->category();

        foreach (['lama' => 1, 'baru' => 0] as $nama => $jamLalu) {
            $karya = Portfolio::create([
                'title'          => "Karya {$nama}",
                'slug'           => Portfolio::generateUniqueSlug("Karya {$nama}"),
                'description'    => 'Deskripsi.',
                'image_path'     => "portfolios/images/{$nama}.webp",
                'thumbnail_path' => "portfolios/thumbs/{$nama}.webp",
                'image_width'    => 1600,
                'image_height'   => 1067,
                'user_id'        => $siswa->id,
                'category_id'    => $category->id,
            ]);
            $karya->forceFill(['created_at' => now()->subHours($jamLalu)])->saveQuietly();
        }

        // Dashboard siswa: "baru" jadi hero (gambar utama), "lama" masuk grid (thumbnail).
        $this->actingAs($siswa)->get(route('siswa.dashboard'))
            ->assertOk()
            ->assertSee('storage/portfolios/images/baru.webp', false)
            ->assertSee('storage/portfolios/thumbs/lama.webp', false)
            ->assertSee('width="480" height="320"', false);

        // Profil publik: semua kartu memakai thumbnail.
        $this->get(route('portfolio.profile', 'siswa-thumb'))
            ->assertOk()
            ->assertSee('storage/portfolios/thumbs/lama.webp', false)
            ->assertSee('storage/portfolios/thumbs/baru.webp', false)
            ->assertSee('width="480" height="320"', false);
    }

    public function test_school_logo_component_renders_with_dimensions(): void
    {
        $this->blade('<x-school-logo class="logo-uji" />')
            ->assertSee('<img', false)
            ->assertSee('alt="Logo SMK Negeri 2 Padang Panjang"', false)
            ->assertSee('class="logo-uji"', false)
            ->assertSee('width="', false)
            ->assertSee('height="', false);
    }

    // ------------------------------------------------------------------
    // Backfill data lama
    // ------------------------------------------------------------------

    public function test_images_optimize_command_backfills_thumbnails_for_legacy_portfolios(): void
    {
        $siswa = $this->siswa();
        $category = $this->category();

        // Karya lama: gambar asli tersimpan, belum ada thumbnail/dimensi.
        $legacy = UploadedFile::fake()->image('asli.png', 1000, 500);
        Storage::disk('public')->put('portfolios/images/lama.png', file_get_contents($legacy->getRealPath()));

        $karya = Portfolio::create([
            'title'       => 'Karya Lama',
            'slug'        => Portfolio::generateUniqueSlug('Karya Lama'),
            'description' => 'Deskripsi.',
            'image_path'  => 'portfolios/images/lama.png',
            'user_id'     => $siswa->id,
            'category_id' => $category->id,
        ]);

        // Karya contoh seeder (berkas tidak ada) harus dilewati tanpa error.
        Portfolio::create([
            'title'       => 'Karya Dummy',
            'slug'        => Portfolio::generateUniqueSlug('Karya Dummy'),
            'description' => 'Deskripsi.',
            'image_path'  => 'portfolios/dummy/1.jpg',
            'user_id'     => $siswa->id,
            'category_id' => $category->id,
        ]);

        $this->artisan('images:optimize')->assertSuccessful();

        $karya->refresh();

        $this->assertSame('portfolios/images/lama.png', $karya->image_path); // gambar utama tidak diubah
        $this->assertSame(1000, $karya->image_width);
        $this->assertSame(500, $karya->image_height);
        $this->assertStringStartsWith('portfolios/thumbs/', $karya->thumbnail_path);
        Storage::disk('public')->assertExists($karya->thumbnail_path);
        $this->assertSame([480, 240], $this->storedSize($karya->thumbnail_path));

        // Dijalankan ulang: aman (idempoten).
        $this->artisan('images:optimize')->assertSuccessful();
    }

    // ------------------------------------------------------------------
    // Unit: perhitungan ukuran
    // ------------------------------------------------------------------

    public function test_fit_scales_down_proportionally_and_never_up(): void
    {
        $this->assertSame([1600, 1067], ImageOptimizer::fit(2400, 1600, 1600));
        $this->assertSame([1067, 1600], ImageOptimizer::fit(1600, 2400, 1600));
        $this->assertSame([300, 200], ImageOptimizer::fit(300, 200, 1600));
        $this->assertSame([480, 480], ImageOptimizer::fit(5000, 5000, 480));
        $this->assertSame([480, 1], ImageOptimizer::fit(5000, 1, 480));
    }
}
