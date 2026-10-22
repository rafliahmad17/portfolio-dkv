<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidatesFileContent;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    use ValidatesFileContent;

    public function __construct(private ImageOptimizer $images)
    {
    }

    /*
    |--------------------------------------------------------------------------
    | PROFIL SISWA
    |--------------------------------------------------------------------------
    */

    /**
     * Tampilkan halaman edit profil siswa.
     */
    public function edit(): View
    {
        $user = Auth::user();

        $skillOptions = User::SKILL_OPTIONS;

        return view('siswa.profile.edit', compact(
            'user',
            'skillOptions'
        ));
    }

    /**
     * Update profil siswa.
     *
     * Data yang disimpan:
     * - Nama
     * - NIS/NIP
     * - Bio
     * - WhatsApp/Kontak
     * - Instagram
     * - Foto
     * - Password
     * - Skills + level + kategori
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'nis_nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'nis_nip')->ignore($user->id),
            ],

            'bio' => [
                'nullable',
                'string',
                'max:500',
            ],

            'contact' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
             * Instagram ditambahkan agar dapat disimpan
             * dan ditampilkan pada PDF portfolio.
             */
            'instagram' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
             * Persetujuan menampilkan kontak (WhatsApp & Instagram) di halaman
             * publik /u/{slug} dan PDF publik. Default: tidak ditampilkan.
             */
            'show_contact' => [
                'nullable',
                'boolean',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            /*
             * Wajib diisi hanya jika siswa memang ingin mengganti
             * password (field 'password' diisi). Diverifikasi dengan
             * Hash::check() terhadap password tersimpan sebelum password
             * baru disimpan -- pola yang sama dengan
             * ProfileController::updatePassword() milik guru.
             */
            'current_password' => [
                'nullable',
                'required_with:password',
                'string',
            ],

            /*
             * Skill bawaan.
             */
            'skills_active' => [
                'nullable',
                'array',
            ],

            'skills_active.*' => [
                'string',
                'max:100',
            ],

            /*
             * Level skill bawaan.
             */
            'skills_level' => [
                'nullable',
                'array',
            ],

            /*
             * Skill custom.
             */
            'custom_skill_name' => [
                'nullable',
                'array',
            ],

            'custom_skill_name.*' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
             * Level skill custom.
             */
            'custom_skill_level' => [
                'nullable',
                'array',
            ],

            'custom_skill_level.*' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],
        ], [
            'nis_nip.unique' => 'NIS/NIP ini sudah digunakan oleh akun lain.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI ISI FILE FOTO (sebelum ada perubahan apa pun)
        |--------------------------------------------------------------------------
        |
        | Dilakukan sebelum file lama disentuh, sehingga unggahan yang isinya
        | tidak valid tidak pernah menghapus foto lama.
        */

        if ($request->hasFile('photo')) {
            $this->validateFileContent($request->file('photo'), ['image/jpeg', 'image/png'], 'photo');
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI PASSWORD SAAT INI (sebelum ada file yang disimpan)
        |--------------------------------------------------------------------------
        |
        | Diverifikasi lebih awal supaya penolakan di sini tidak meninggalkan
        | file foto baru yang yatim di storage.
        */

        if (
            !empty($validated['password'])
            && !Hash::check($validated['current_password'], $user->password)
        ) {
            return back()
                ->withErrors([
                    'current_password' =>
                        'Password saat ini yang Anda masukkan salah.',
                ])
                ->onlyInput('current_password');
        }

        /*
        |--------------------------------------------------------------------------
        | FOTO PROFIL
        |--------------------------------------------------------------------------
        |
        | Urutan aman: simpan file baru dulu. File lama baru dihapus setelah
        | data user berhasil disimpan ke database.
        */

        $oldPhotoPath = $user->photo;
        $photoPath = $oldPhotoPath;
        $newPhotoPath = null;

        if ($request->hasFile('photo')) {
            $newPhotoPath = $this->images->storeResized(
                $request->file('photo'),
                'profiles/photos',
                512
            );
            $photoPath = $newPhotoPath;
        }

        /*
        |--------------------------------------------------------------------------
        | SUSUN DATA SKILL
        |--------------------------------------------------------------------------
        |
        | Format yang disimpan:
        |
        | [
        |     [
        |         'name'  => 'Adobe Photoshop',
        |         'level' => 80,
        |         'type'  => 'Software Desain',
        |     ],
        |     [
        |         'name'  => 'Ilustrasi Digital',
        |         'level' => 85,
        |         'type'  => 'Kompetensi Inti',
        |     ],
        | ]
        |
        |--------------------------------------------------------------------------
        */

        $skills = [];

        /*
        |--------------------------------------------------------------------------
        | SKILL BAWAAN
        |--------------------------------------------------------------------------
        */

        $activeSkills = $validated['skills_active'] ?? [];

        foreach (User::SKILL_OPTIONS as $group => $options) {

            foreach ($options as $skillName) {

                /*
                 * Cek apakah siswa memilih skill ini.
                 */
                if (in_array($skillName, $activeSkills, true)) {

                    /*
                     * Ambil level.
                     *
                     * Nama skill digunakan sebagai key:
                     * skills_level[Adobe Photoshop]
                     */
                    $level = (int) (
                        $request->input(
                            "skills_level.{$skillName}"
                        ) ?? 50
                    );

                    /*
                     * Pastikan level 0-100.
                     */
                    $level = max(
                        0,
                        min(100, $level)
                    );

                    $skills[] = [
                        'name' => $skillName,
                        'level' => $level,
                        'type' => $group,
                    ];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SKILL CUSTOM
        |--------------------------------------------------------------------------
        */

        $customNames = $validated['custom_skill_name'] ?? [];
        $customLevels = $validated['custom_skill_level'] ?? [];

        foreach ($customNames as $index => $customName) {

            $customName = trim((string) $customName);

            /*
             * Jangan simpan skill custom kosong.
             */
            if ($customName === '') {
                continue;
            }

            $customLevel = (int) (
                $customLevels[$index] ?? 50
            );

            $customLevel = max(
                0,
                min(100, $customLevel)
            );

            $skills[] = [
                'name' => $customName,
                'level' => $customLevel,
                'type' => 'Custom',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA PROFIL
        |--------------------------------------------------------------------------
        */

        $user->name = $validated['name'];

        $user->nis_nip = $validated['nis_nip'] ?? null;

        $user->bio = $validated['bio'] ?? null;

        $user->contact = $validated['contact'] ?? null;

        /*
         * Instagram sekarang ikut disimpan.
         */
        $user->instagram = $validated['instagram'] ?? null;

        /*
         * Persetujuan tampil di halaman publik (default false).
         */
        $user->show_contact = $request->boolean('show_contact');

        $user->photo = $photoPath;

        /*
         * Skills disimpan sebagai array.
         * User model sudah menggunakan cast 'array'.
         */
        $user->skills = $skills;

        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['password'])) {

            /*
             * current_password sudah diverifikasi di atas (sebelum file
             * apa pun disimpan), jadi di sini cukup menyimpan hash baru.
             */
            $user->password = Hash::make(
                $validated['password']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN KE DATABASE
        |--------------------------------------------------------------------------
        */

        try {
            $user->save();
        } catch (\Throwable $e) {
            // Simpan gagal: buang file baru agar tidak yatim, foto lama tetap utuh.
            if ($newPhotoPath) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            throw $e;
        }

        /*
         * Database sudah menunjuk ke foto baru -> baru aman menghapus foto lama.
         */
        if ($newPhotoPath && $oldPhotoPath && $oldPhotoPath !== $newPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE PROFIL
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('siswa.profile.edit')
            ->with(
                'success',
                'Profil berhasil diperbarui! ✏️'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PROFIL GURU
    |--------------------------------------------------------------------------
    */

    /**
     * Tampilkan halaman profil guru.
     */
    public function guruShow(): View
    {
        return view('guru.profile');
    }

    /**
     * Update biodata guru:
     * nama, NIP, email, dan foto.
     */
    public function guruUpdate(
        Request $request
    ): RedirectResponse {

        $user = Auth::user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'nis_nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(
                    'users',
                    'nis_nip'
                )->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'nis_nip.unique' => 'NIS/NIP ini sudah digunakan oleh akun lain.',
            'email.unique'   => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FOTO GURU
        |--------------------------------------------------------------------------
        |
        | Urutan aman: validasi isi file -> simpan file baru -> simpan DB ->
        | baru hapus file lama.
        */

        if ($request->hasFile('avatar')) {
            $this->validateFileContent($request->file('avatar'), ['image/jpeg', 'image/png', 'image/webp'], 'avatar');
        }

        $oldPhotoPath = $user->photo;
        $photoPath = $oldPhotoPath;
        $newPhotoPath = null;

        if ($request->hasFile('avatar')) {
            $newPhotoPath = $this->images->storeResized(
                $request->file('avatar'),
                'profiles/photos',
                512
            );
            $photoPath = $newPhotoPath;
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA GURU
        |--------------------------------------------------------------------------
        */

        $user->name = $validated['name'];

        $user->nis_nip = $validated['nis_nip'] ?? null;

        $user->email = $validated['email'];

        $user->photo = $photoPath;

        try {
            $user->save();
        } catch (\Throwable $e) {
            if ($newPhotoPath) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            throw $e;
        }

        if ($newPhotoPath && $oldPhotoPath && $oldPhotoPath !== $newPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return redirect()
            ->route('guru.profile')
            ->with(
                'success',
                'Biodata berhasil diperbarui!'
            );
    }

    /**
     * Update password guru.
     *
     * Memerlukan password saat ini
     * untuk verifikasi.
     */
    public function updatePassword(
        Request $request
    ): RedirectResponse {

        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI PASSWORD LAMA
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $validated['current_password'],
                $user->password
            )
        ) {

            return back()
                ->withErrors([
                    'current_password' =>
                        'Password saat ini yang Anda masukkan salah.',
                ])
                ->onlyInput(
                    'current_password'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | PASSWORD BARU
        |--------------------------------------------------------------------------
        */

        $user->password = Hash::make(
            $validated['password']
        );

        $user->save();

        return redirect()
            ->route('guru.profile')
            ->with(
                'success',
                'Password berhasil diperbarui!'
            );
    }
}
