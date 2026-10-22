<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidatesFileContent;
use App\Models\Achievement;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    use ValidatesFileContent;

    public function __construct(private ImageOptimizer $images)
    {
    }

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

        // Periksa isi file yang diunggah SEBELUM menyentuh file lama, supaya
        // unggahan yang isinya tidak valid tidak pernah menghapus file yang ada.
        $this->validateUploadedContent($request);

        // Jangan masukkan object UploadedFile ke database
        unset($validated['image'], $validated['file']);

        // File lama baru dihapus SETELAH database menunjuk ke file baru.
        $oldFiles = [];

        if ($request->hasFile('image')) {
            $validated['image_path'] = $this->storeFile(
                $request,
                'image',
                'achievements/photos'
            );
            $oldFiles[] = $achievement->image_path;
        }

        if ($request->hasFile('file')) {
            $validated['file_path'] = $this->storeFile(
                $request,
                'file',
                'achievements/docs'
            );
            $oldFiles[] = $achievement->file_path;
        }

        $achievement->update($validated);

        $oldFiles = array_filter($oldFiles);
        if ($oldFiles) {
            Storage::disk('public')->delete($oldFiles);
        }

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
     * Validasi isi file (finfo) untuk semua unggahan di request, tanpa menyimpan
     * atau menghapus apa pun. Dipanggil lebih dulu sebelum file lama disentuh.
     */
    private function validateUploadedContent(Request $request): void
    {
        if ($request->hasFile('image')) {
            $this->validateFileContent($request->file('image'), ['image/jpeg', 'image/png'], 'image');
        }

        if ($request->hasFile('file')) {
            $this->validateFileContent($request->file('file'), ['application/pdf'], 'file');
        }
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

        // Gambar sertifikat/prestasi diperkecil & dikonversi ke WebP; PDF disimpan apa adanya.
        if ($field === 'image') {
            return $this->images->storeResized($file, $folder, 1280);
        }

        return $file->store($folder, 'public');
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