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
    public function edit(): View
    {
        $user = Auth::user();
        $skillOptions = User::SKILL_OPTIONS;

        return view('siswa.profile.edit', compact('user', 'skillOptions'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'nis_nip'          => ['nullable', 'string', 'max:50'],
            'bio'              => ['nullable', 'string', 'max:500'],
            'contact'          => ['nullable', 'string', 'max:255'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
            'skills_active'    => ['nullable', 'array'],
            'skills_active.*'  => ['string', 'max:100'],
            'skills_level'     => ['nullable', 'array'],
            'custom_skill_name'  => ['nullable', 'array'],
            'custom_skill_name.*' => ['nullable', 'string', 'max:100'],
            'custom_skill_level' => ['nullable', 'array'],
            'custom_skill_level.*' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $photoPath = $user->photo;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('profiles/photos', 'public');
        }

        // Susun ulang data skill dari daftar baku + skill custom siswa
        $skills = [];
        foreach (User::SKILL_OPTIONS as $group => $options) {
            foreach ($options as $skillName) {
                if (in_array($skillName, $validated['skills_active'] ?? [], true)) {
                    $level = (int) ($request->input("skills_level.$skillName") ?? 50);
                    $skills[] = [
                        'name'  => $skillName,
                        'level' => max(0, min(100, $level)),
                        'type'  => $group,
                    ];
                }
            }
        }
        $customNames  = $validated['custom_skill_name'] ?? [];
        $customLevels = $validated['custom_skill_level'] ?? [];
        foreach ($customNames as $i => $customName) {
            if (!empty(trim((string) $customName))) {
                $skills[] = [
                    'name'  => trim($customName),
                    'level' => max(0, min(100, (int) ($customLevels[$i] ?? 50))),
                    'type'  => 'Custom',
                ];
            }
        }

        $user->name    = $validated['name'];
        $user->nis_nip = $validated['nis_nip'] ?? null;
        $user->bio     = $validated['bio'] ?? null;
        $user->contact = $validated['contact'] ?? null;
        $user->photo   = $photoPath;
        $user->skills  = $skills;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('siswa.profile.edit')
                         ->with('success', 'Profil berhasil diperbarui! ✏️');
    }

    /**
     * Tampilkan halaman profil guru.
     */
    public function guruShow(): View
    {
        return view('guru.profile');
    }

    /**
     * Update biodata guru: nama, NIP, email, dan foto (avatar).
     */
    public function guruUpdate(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'nis_nip' => ['nullable', 'string', 'max:50'],
            'email'   => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'avatar'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photoPath = $user->photo;
        if ($request->hasFile('avatar')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('avatar')->store('profiles/photos', 'public');
        }

        $user->name    = $validated['name'];
        $user->nis_nip = $validated['nis_nip'] ?? null;
        $user->email   = $validated['email'];
        $user->photo   = $photoPath;
        $user->save();

        return redirect()->route('guru.profile')
                         ->with('success', 'Biodata berhasil diperbarui!');
    }

    /**
     * Update password guru — memerlukan password saat ini untuk verifikasi.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini yang Anda masukkan salah.'])
                ->onlyInput('current_password');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('guru.profile')
                         ->with('success', 'Password berhasil diperbarui!');
    }
}