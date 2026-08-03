<?php
// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar seluruh kategori beserta jumlah karya yang memakai
     * masing-masing kategori. Diurutkan alfabetis dan dipaginasi agar tetap
     * ringan meski jumlah kategori bertambah banyak di kemudian hari.
     */
    public function index(): View
    {
        $categories = Category::withCount('portfolios')
            ->orderBy('name')
            ->paginate(12);

        // Statistik ringkasan untuk kartu di bagian atas halaman
        $totalKategori = Category::count();
        $totalKaryaTerkategori = Portfolio::whereNotNull('category_id')->count();

        return view('guru.kategori.index', [
            'categories'            => $categories,
            'totalKategori'         => $totalKategori,
            'totalKaryaTerkategori' => $totalKaryaTerkategori,
        ]);
    }

    /**
     * Simpan kategori baru. Slug dibuat otomatis dari nama menggunakan
     * Str::slug() agar konsisten dan aman dipakai di URL.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
            'name.unique'   => "Nama kategori ':input' sudah digunakan, coba nama lain.",
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('guru.kategori.index')
            ->with('success', "Kategori '{$validated['name']}' berhasil ditambahkan!");
    }

    /**
     * Perbarui kategori yang sudah ada. Aturan unique mengabaikan baris
     * kategori itu sendiri, dan slug ikut diperbarui mengikuti nama baru.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        // Pakai error bag terpisah ('editCategory') supaya pesan validasi modal
        // edit tidak tercampur dengan pesan validasi form tambah kategori di
        // halaman yang sama — keduanya berbagi nama field 'name'.
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
            'name.unique'   => "Nama kategori ':input' sudah digunakan, coba nama lain.",
        ]);
        $validator->validateWithBag('editCategory');
        $validated = $validator->validated();

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('guru.kategori.index')
            ->with('success', "Kategori '{$validated['name']}' berhasil diperbarui!");
    }

    /**
     * Hapus kategori — tapi ditolak jika kategori tersebut masih dipakai
     * oleh satu atau lebih karya siswa. Ini krusial agar category_id di
     * tabel portfolios tidak tiba-tiba rusak/null tanpa sepengetahuan siswa.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $jumlahKarya = $category->portfolios()->count();

        if ($jumlahKarya > 0) {
            return back()->with(
                'error',
                "Kategori '{$category->name}' tidak bisa dihapus karena masih dipakai {$jumlahKarya} karya. Pindahkan karya ke kategori lain terlebih dahulu."
            );
        }

        $namaKategori = $category->name;
        $category->delete();

        return redirect()->route('guru.kategori.index')
            ->with('success', "Kategori '{$namaKategori}' berhasil dihapus.");
    }
}