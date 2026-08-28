<?php

namespace App\Http\Controllers;

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
        ]);

        /*
        |--------------------------------------------------------------------------
        | FOTO PROFIL
        |--------------------------------------------------------------------------
        */

        $photoPath = $user->photo;

        if ($request->hasFile('photo')) {

            /*
             * Hapus foto lama jika ada.
             */
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            /*
             * Simpan foto baru dengan validasi konten server-side.
             */
            $this->validateFileContent($request->file('photo'), ['image/jpeg', 'image/png'], 'photo');
            $photoPath = $request
                ->file('photo')
                ->store('profiles/photos', 'public');
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

            $user->password = Hash::make(
                $validated['password']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN KE DATABASE
        |--------------------------------------------------------------------------
        */

        $user->save();

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
        ]);

        /*
        |--------------------------------------------------------------------------
        | FOTO GURU
        |--------------------------------------------------------------------------
        */

        $photoPath = $user->photo;

        if ($request->hasFile('avatar')) {

            if ($photoPath) {
                Storage::disk('public')->delete(
                    $photoPath
                );
            }

            $this->validateFileContent($request->file('avatar'), ['image/jpeg', 'image/png', 'image/webp'], 'avatar');
            $photoPath = $request
                ->file('avatar')
                ->store(
                    'profiles/photos',
                    'public'
                );
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

        $user->save();

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

    /**
     * Validate actual file content using finfo (server-side MIME check).
     */
    private function validateFileContent($file, array $allowedMimes, string $field): void
    {
        if (!$file || !$file->isValid()) {
            return;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file->getRealPath());

        if (!in_array($mime, $allowedMimes, true)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $field => "File tidak valid. Tipe file yang diizinkan: " . implode(', ', $allowedMimes),
            ]);
        }
    }
}