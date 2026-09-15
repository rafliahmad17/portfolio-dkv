<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FASE 7.2 — Security & Authorization Tests.
 *
 * Membuktikan ownership check di AchievementController (abort_unless 403)
 * benar mencegah satu siswa mengubah/menghapus achievement (prestasi/
 * sertifikat) milik siswa lain (IDOR). Tidak mengubah authorization logic
 * production — hanya membuktikan yang sudah ada.
 */
class AchievementOwnershipTest extends TestCase
{
    use RefreshDatabase;

    private function makeAchievementFor(User $owner): Achievement
    {
        return Achievement::create([
            'user_id'     => $owner->id,
            'type'        => 'sertifikat',
            'title'       => 'Sertifikat Milik Owner',
            'issuer'      => 'Panitia Lomba',
            'description' => 'Deskripsi.',
            'achieved_at' => '2026-01-01',
        ]);
    }

    public function test_student_cannot_update_another_students_achievement(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $attacker = User::factory()->create(['role' => 'siswa']);
        $achievement = $this->makeAchievementFor($owner);

        $response = $this->actingAs($attacker)->put(
            route('siswa.achievement.update', $achievement),
            [
                'type'  => 'sertifikat',
                'title' => 'Judul Diubah Paksa',
            ]
        );

        $response->assertForbidden();
        $this->assertSame('Sertifikat Milik Owner', $achievement->fresh()->title);
    }

    public function test_student_cannot_delete_another_students_achievement(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $attacker = User::factory()->create(['role' => 'siswa']);
        $achievement = $this->makeAchievementFor($owner);

        $response = $this->actingAs($attacker)->delete(
            route('siswa.achievement.destroy', $achievement)
        );

        $response->assertForbidden();
        $this->assertModelExists($achievement);
    }

    /**
     * Kontrol positif: membuktikan bahwa test IDOR di atas benar-benar
     * menguji ownership check, bukan sekadar route yang selalu menolak
     * semua request PUT/DELETE.
     */
    public function test_student_can_still_update_their_own_achievement(): void
    {
        $owner = User::factory()->create(['role' => 'siswa']);
        $achievement = $this->makeAchievementFor($owner);

        $response = $this->actingAs($owner)->put(
            route('siswa.achievement.update', $achievement),
            [
                'type'  => 'sertifikat',
                'title' => 'Judul Baru',
            ]
        );

        $response->assertRedirect(route('siswa.achievement.index'));
        $this->assertSame('Judul Baru', $achievement->fresh()->title);
    }
}
