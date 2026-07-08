<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    /**
     * Daftar prestasi & sertifikat milik siswa yang login.
     */
    public function index(): View
    {
        $achievements = Achievement::where('user_id', Auth::id())
            ->latest('achieved_at')
            ->get();

        return view('siswa.achievement.index', compact('achievements'));
    }

    /**
     * Simpan prestasi/sertifikat baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['user_id'] = Auth::id();
        $validated['image_path'] = $this->storeFile($request, 'image', 'achievements/photos');
        $validated['file_path']  = $this->storeFile($request, 'file', 'achievements/docs');

        Achievement::create($validated);

        return redirect()->route('siswa.achievement.index')
            ->with('success', 'Prestasi/sertifikat berhasil ditambahkan!');
    }

    /**
     * Form edit prestasi/sertifikat.
     */
    public function edit(Achievement $achievement): View
    {
        $this->authorizeOwner($achievement);

        return view('siswa.achievement.edit', compact('achievement'));
    }

    /**
     * Update prestasi/sertifikat.
     */
    public function update(Request $request, Achievement $achievement): RedirectResponse
    {
        $this->authorizeOwner($achievement);

        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($achievement->image_path) {
                Storage::disk('public')->delete($achievement->image_path);
            }
            $validated['image_path'] = $this->storeFile($request, 'image', 'achievements/photos');
        }

        if ($request->hasFile('file')) {
            if ($achievement->file_path) {
                Storage::disk('public')->delete($achievement->file_path);
            }
            $validated['file_path'] = $this->storeFile($request, 'file', 'achievements/docs');
        }

        $achievement->update($validated);

        return redirect()->route('siswa.achievement.index')
            ->with('success', 'Prestasi/sertifikat berhasil diperbarui!');
    }

    /**
     * Hapus prestasi/sertifikat.
     */
    public function destroy(Achievement $achievement): RedirectResponse
    {
        $this->authorizeOwner($achievement);

        if ($achievement->image_path) {
            Storage::disk('public')->delete($achievement->image_path);
        }
        if ($achievement->file_path) {
            Storage::disk('public')->delete($achievement->file_path);
        }

        $achievement->delete();

        return redirect()->route('siswa.achievement.index')
            ->with('success', 'Prestasi/sertifikat berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'type'        => ['required', 'in:prestasi,sertifikat'],
            'title'       => ['required', 'string', 'max:255'],
            'issuer'      => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'achieved_at' => ['nullable', 'date'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'file'        => ['nullable', 'file', 'mimes:pdf', 'max:4096'],
        ]);
    }

    private function storeFile(Request $request, string $field, string $folder): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($folder, 'public');
    }

    private function authorizeOwner(Achievement $achievement): void
    {
        abort_unless($achievement->user_id === Auth::id(), 403);
    }
}