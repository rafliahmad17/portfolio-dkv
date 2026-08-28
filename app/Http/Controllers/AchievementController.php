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

        // Simpan file gambar jika ada
        $imagePath = $this->storeFile(
            $request,
            'image',
            'achievements/photos'
        );

        // Simpan file PDF jika ada
        $filePath = $this->storeFile(
            $request,
            'file',
            'achievements/docs'
        );

        // Jangan masukkan UploadedFile ke database
        unset($validated['image'], $validated['file']);

        $validated['user_id'] = Auth::id();
        $validated['image_path'] = $imagePath;
        $validated['file_path'] = $filePath;

        Achievement::create($validated);

        return redirect()
            ->route('siswa.achievement.index')
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
    public function update(
        Request $request,
        Achievement $achievement
    ): RedirectResponse {
        $this->authorizeOwner($achievement);

        $validated = $this->validated($request);

        // Jangan masukkan object UploadedFile ke database
        unset($validated['image'], $validated['file']);

        /*
         * Jika ada gambar baru:
         * hapus gambar lama, kemudian simpan gambar baru.
         */
        if ($request->hasFile('image')) {

            if ($achievement->image_path) {
                Storage::disk('public')
                    ->delete($achievement->image_path);
            }

            $validated['image_path'] = $this->storeFile(
                $request,
                'image',
                'achievements/photos'
            );
        }

        /*
         * Jika ada PDF baru:
         * hapus PDF lama, kemudian simpan PDF baru.
         */
        if ($request->hasFile('file')) {

            if ($achievement->file_path) {
                Storage::disk('public')
                    ->delete($achievement->file_path);
            }

            $validated['file_path'] = $this->storeFile(
                $request,
                'file',
                'achievements/docs'
            );
        }

        $achievement->update($validated);

        return redirect()
            ->route('siswa.achievement.index')
            ->with('success', 'Prestasi/sertifikat berhasil diperbarui!');
    }

    /**
     * Hapus prestasi/sertifikat.
     */
    public function destroy(
        Achievement $achievement
    ): RedirectResponse {
        $this->authorizeOwner($achievement);

        // Hapus gambar
        if ($achievement->image_path) {
            Storage::disk('public')
                ->delete($achievement->image_path);
        }

        // Hapus PDF
        if ($achievement->file_path) {
            Storage::disk('public')
                ->delete($achievement->file_path);
        }

        // Hapus data database
        $achievement->delete();

        return redirect()
            ->route('siswa.achievement.index')
            ->with(
                'success',
                'Prestasi/sertifikat berhasil dihapus.'
            );
    }

    /**
     * Validasi data.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'type' => [
                'required',
                'in:prestasi,sertifikat'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'issuer' => [
                'nullable',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'achieved_at' => [
                'nullable',
                'date'
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:4096'
            ],
        ]);
    }

    /**
     * Simpan file ke storage public dengan validasi konten server-side.
     */
    private function storeFile(
        Request $request,
        string $field,
        string $folder
    ): ?string {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $allowedMimes = $field === 'image'
            ? ['image/jpeg', 'image/png']
            : ['application/pdf'];

        $this->validateFileContent($file, $allowedMimes, $field);

        return $file->store($folder, 'public');
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

    /**
     * Pastikan data hanya bisa diakses pemiliknya.
     */
    private function authorizeOwner(
        Achievement $achievement
    ): void {
        abort_unless(
            $achievement->user_id === Auth::id(),
            403
        );
    }
}