<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Lapisan pertahanan: 'role' tidak boleh mass-assignable di model User.
 *
 * Belum ada jalur yang bisa dieksploitasi, tetapi jika suatu hari ada kode
 * yang memakai $request->all() atau fill() pada User, role tidak boleh ikut
 * terisi dari input pengguna. Test ini juga membuktikan semua jalur sah yang
 * memang perlu mengisi role (pendaftaran siswa oleh guru, seeder) tetap jalan.
 */
class UserMassAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_is_not_in_fillable(): void
    {
        $this->assertNotContains('role', (new User)->getFillable());
    }

    public function test_mass_assigning_role_is_ignored(): void
    {
        $user = new User([
            'name'  => 'Penyusup',
            'email' => 'penyusup@example.test',
            'role'  => 'admin',
        ]);

        $this->assertNull($user->role);
    }

    public function test_teacher_registering_a_student_still_creates_role_siswa(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $this->actingAs($guru)
            ->post(route('guru.siswa.store'), [
                'name'     => 'Siswa Baru Uji',
                'email'    => 'siswa.baru.uji@example.test',
                'nis_nip'  => '2024111222',
                'password' => 'passwordAwal123',
            ])
            ->assertRedirect(route('guru.siswa.index'));

        $siswa = User::where('email', 'siswa.baru.uji@example.test')->firstOrFail();

        $this->assertSame('siswa', $siswa->role);
        $this->assertNotEmpty($siswa->portfolio_slug);
    }

    public function test_student_cannot_escalate_role_through_profile_update(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'name' => 'Siswa Biasa']);

        $this->actingAs($siswa)
            ->put(route('siswa.profile.update'), [
                'name' => 'Siswa Biasa',
                'role' => 'admin',
            ])
            ->assertRedirect(route('siswa.profile.edit'));

        $this->assertSame('siswa', $siswa->fresh()->role);
    }

    public function test_user_seeder_still_assigns_roles_and_slugs(): void
    {
        $this->seed(UserSeeder::class);

        $this->assertSame('admin', User::where('email', 'admin@smkn2.sch.id')->value('role'));
        $this->assertSame('guru', User::where('email', 'guru@smkn2.sch.id')->value('role'));
        // 1 akun siswa demo (email tetap) + 12 siswa acak.
        $this->assertSame(13, User::where('role', 'siswa')->count());
        $this->assertSame('siswa', User::where('email', 'siswa@smkn2.sch.id')->value('role'));
        $this->assertSame('siswa-demo', User::where('email', 'siswa@smkn2.sch.id')->value('portfolio_slug'));
        $this->assertSame(0, User::where('role', 'siswa')->whereNull('portfolio_slug')->count());
    }
}
