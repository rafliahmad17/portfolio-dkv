<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * TAHAP 2 poin 11 -- Locale Indonesia dan zona waktu WIB.
 *
 * Bug asal: locale=en dan timezone=UTC, sehingga pesan validasi bawaan
 * berbahasa Inggris dan tanggal bisa bergeser 7 jam dari waktu setempat.
 */
class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_uses_indonesian_locale_and_jakarta_timezone(): void
    {
        $this->assertSame('id', config('app.locale'));
        $this->assertSame('id', app()->getLocale());
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', now()->timezoneName);
    }

    public function test_default_validation_messages_are_in_indonesian(): void
    {
        $validator = Validator::make(
            ['email' => 'bukan-email', 'password' => '123'],
            ['name' => 'required', 'email' => 'email', 'password' => 'min:8'],
        );

        $errors = $validator->errors();

        $this->assertSame('Nama wajib diisi.', $errors->first('name'));
        $this->assertSame('Format alamat email tidak valid.', $errors->first('email'));
        $this->assertSame('Password minimal terdiri dari 8 karakter.', $errors->first('password'));
    }

    public function test_validation_attribute_names_are_friendly(): void
    {
        $validator = Validator::make([], ['category_id' => 'required', 'file_pdf' => 'required']);

        $this->assertSame('Kategori wajib diisi.', $validator->errors()->first('category_id'));
        $this->assertSame('Berkas PDF wajib diisi.', $validator->errors()->first('file_pdf'));
    }

    public function test_relative_dates_are_humanized_in_indonesian(): void
    {
        $this->assertSame('2 jam yang lalu', Carbon::now()->subHours(2)->diffForHumans());
    }

    public function test_login_validation_errors_are_not_in_english(): void
    {
        $response = $this->post('/login', ['email' => '', 'password' => '']);

        $response->assertSessionHasErrors([
            'email'    => 'Alamat email wajib diisi.',
            'password' => 'Password wajib diisi.',
        ]);
    }

    public function test_created_at_is_stored_in_the_application_timezone(): void
    {
        $this->travelTo(Carbon::parse('2026-10-21 08:30:00', 'Asia/Jakarta'));

        $user = User::factory()->create(['role' => 'siswa']);

        $this->assertSame('2026-10-21 08:30:00', $user->created_at->format('Y-m-d H:i:s'));
        $this->assertSame('Asia/Jakarta', $user->created_at->timezoneName);
    }
}
