<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TAHAP 1 poin 4 -- Privasi data pribadi siswa di halaman publik.
 *
 * Aturan yang dibuktikan di sini:
 * - NIS dan email siswa TIDAK PERNAH tampil di /u/{slug}, /u/{slug}/print,
 *   maupun /p/{slug}.
 * - Nomor WhatsApp & Instagram hanya tampil jika siswa mengaktifkan
 *   "show_contact" (default: tidak tampil).
 * - Pilihan show_contact bisa disimpan dari halaman Profil Saya.
 */
class PublicPrivacyTest extends TestCase
{
    use RefreshDatabase;

    private const NIS       = '2024987654';
    private const CONTACT   = '081234567890';
    private const INSTAGRAM = '@siswa_privasi_uji';
    private const EMAIL     = 'siswa.privasi@example.test';

    private function makeStudent(bool $showContact): User
    {
        $student = User::factory()->create([
            'role'         => 'siswa',
            'name'         => 'Siswa Privasi Uji',
            'email'        => self::EMAIL,
            'nis_nip'      => self::NIS,
            'contact'      => self::CONTACT,
            'instagram'    => self::INSTAGRAM,
            'show_contact' => $showContact,
        ]);

        // portfolio_slug tidak ada di $fillable -- sama seperti di production.
        $student->portfolio_slug = 'siswa-privasi-uji-' . ($showContact ? 'on' : 'off');
        $student->save();

        return $student;
    }

    private function makePortfolioFor(User $owner): Portfolio
    {
        $category = Category::create(['name' => 'Poster', 'slug' => 'poster']);

        return Portfolio::create([
            'title'       => 'Karya Uji Privasi',
            'slug'        => Portfolio::generateUniqueSlug('Karya Uji Privasi'),
            'description' => 'Deskripsi karya untuk pengujian privasi.',
            'image_path'  => 'portfolios/images/dummy.jpg',
            'user_id'     => $owner->id,
            'category_id' => $category->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Profil publik /u/{slug}
    |--------------------------------------------------------------------------
    */

    public function test_public_profile_never_shows_nis_or_email(): void
    {
        // Kasus paling ketat: siswa sudah mengaktifkan show_contact, tetapi
        // NIS dan email tetap tidak boleh muncul.
        $student = $this->makeStudent(true);

        $response = $this->get(route('portfolio.profile', $student->portfolio_slug));

        $response->assertOk();
        $response->assertDontSee(self::NIS);
        $response->assertDontSee(self::EMAIL);
    }

    public function test_public_profile_hides_contact_by_default(): void
    {
        $student = $this->makeStudent(false);

        $response = $this->get(route('portfolio.profile', $student->portfolio_slug));

        $response->assertOk();
        $response->assertDontSee(self::CONTACT);
        $response->assertDontSee('wa.me');
    }

    public function test_public_profile_shows_contact_when_student_opted_in(): void
    {
        $student = $this->makeStudent(true);

        $response = $this->get(route('portfolio.profile', $student->portfolio_slug));

        $response->assertOk();
        $response->assertSee(self::CONTACT);
        $response->assertSee('wa.me/6281234567890', false);
    }

    /*
    |--------------------------------------------------------------------------
    | PDF publik /u/{slug}/print
    |--------------------------------------------------------------------------
    */

    public function test_public_print_never_shows_nis_or_email(): void
    {
        $student = $this->makeStudent(true);
        $this->makePortfolioFor($student);

        $response = $this->get(route('portfolio.public.print', $student->portfolio_slug));

        $response->assertOk();
        $response->assertDontSee(self::NIS);
        $response->assertDontSee(self::EMAIL);
    }

    public function test_public_print_hides_contact_by_default(): void
    {
        $student = $this->makeStudent(false);

        $response = $this->get(route('portfolio.public.print', $student->portfolio_slug));

        $response->assertOk();
        $response->assertDontSee(self::CONTACT);
        $response->assertDontSee(self::INSTAGRAM);
    }

    public function test_public_print_shows_contact_when_student_opted_in(): void
    {
        $student = $this->makeStudent(true);

        $response = $this->get(route('portfolio.public.print', $student->portfolio_slug));

        $response->assertOk();
        $response->assertSee(self::CONTACT);
        $response->assertSee(self::INSTAGRAM);
    }

    /*
    |--------------------------------------------------------------------------
    | Detail karya /p/{slug}
    |--------------------------------------------------------------------------
    */

    public function test_public_portfolio_detail_never_shows_nis(): void
    {
        $student   = $this->makeStudent(true);
        $portfolio = $this->makePortfolioFor($student);

        $response = $this->get(route('portfolio.public', $portfolio->slug));

        $response->assertOk();
        $response->assertSee($student->name);
        $response->assertDontSee(self::NIS);
        $response->assertDontSee(self::EMAIL);
    }

    /*
    |--------------------------------------------------------------------------
    | Penyimpanan pilihan show_contact dari Profil Saya
    |--------------------------------------------------------------------------
    */

    public function test_show_contact_defaults_to_false_for_new_students(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $this->assertFalse((bool) $student->fresh()->show_contact);
    }

    public function test_student_can_opt_in_and_opt_out_of_showing_contact(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'name' => 'Siswa Toggle']);

        $this->actingAs($student)
            ->put(route('siswa.profile.update'), [
                'name'         => 'Siswa Toggle',
                'show_contact' => '1',
            ])
            ->assertRedirect(route('siswa.profile.edit'));

        $this->assertTrue((bool) $student->fresh()->show_contact);

        // Checkbox tidak dicentang -> browser tidak mengirim field sama sekali.
        $this->actingAs($student)
            ->put(route('siswa.profile.update'), [
                'name' => 'Siswa Toggle',
            ])
            ->assertRedirect(route('siswa.profile.edit'));

        $this->assertFalse((bool) $student->fresh()->show_contact);
    }
}
