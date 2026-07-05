<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();

        return view('siswa.profile.edit', compact('user'));
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
        ]);

        $photoPath = $user->photo;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('profiles/photos', 'public');
        }

        $user->name    = $validated['name'];
        $user->nis_nip = $validated['nis_nip'] ?? null;
        $user->bio     = $validated['bio'] ?? null;
        $user->contact = $validated['contact'] ?? null;
        $user->photo   = $photoPath;

        if (!empty($validated['password'])) {
                $user->password = $validated['password'];
            }
        $user->save();

        return redirect()->route('siswa.profile.edit')
                         ->with('success', 'Profil berhasil diperbarui! ✏️');
    }
}