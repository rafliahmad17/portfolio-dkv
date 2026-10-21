<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function siswa()
    {
        // Backfill defensif: hanya untuk akun siswa lama yang entah bagaimana
        // masih belum punya portfolio_slug (mis. dibuat lewat tinker/seeder
        // lama). Memakai sumber kebenaran tunggal User::generateUniquePortfolioSlug()
        // (sama seperti StudentController) supaya TIDAK PERNAH membocorkan ID
        // database ke URL publik. Tidak menimpa slug yang sudah ada, dan tidak
        // menjalankan regenerasi apa pun jika slug sudah tersedia.
        $user = Auth::user();
        if (empty($user->portfolio_slug)) {
            $user->portfolio_slug = User::generateUniquePortfolioSlug($user->name);
            $user->save();
        }

        // Karya terbaru dipajang besar sebagai hero (selalu karya terbaru milik
        // siswa, di halaman berapa pun). Sisanya masuk grid yang dipaginasi
        // 12 per halaman, sehingga dashboard tidak memuat seluruh karya sekaligus.
        // Urutan memakai created_at lalu id agar stabil saat dua karya dibuat
        // pada detik yang sama (tanpa ini karya bisa tampil dobel/hilang antar halaman).
        $ownWorks = fn () => Portfolio::with('category')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $totalKarya = Portfolio::where('user_id', Auth::id())->count();

        $featured = $ownWorks()->first();

        $portfolios = $ownWorks()
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->paginate(12)
            ->withQueryString();

        // Statistik per kategori dihitung dari category_id karya siswa ini, BUKAN
        // dari slug kategori yang ditulis tetap di kode. Slug berubah setiap kali
        // guru mengganti nama kategori (CategoryController::update), sehingga
        // pencarian berdasarkan slug tetap membuat hitungan menjadi 0.
        // Dua kategori yang paling banyak dipakai siswa ditampilkan dengan nama
        // yang tersimpan saat ini. Dihitung lewat GROUP BY di database (bukan
        // dari koleksi yang dimuat penuh) supaya tetap benar setelah paginasi.
        // Hasil selalu berisi 2 slot (slot kosong total 0).
        $kategoriTeratas = Portfolio::with('category')
            ->where('user_id', Auth::id())
            ->whereNotNull('category_id')
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->orderBy('category_id')
            ->limit(2)
            ->get()
            ->map(fn ($row) => [
                'name'  => $row->category?->name ?? 'Tanpa Kategori',
                'total' => (int) $row->total,
            ])
            ->values()
            ->all();

        while (count($kategoriTeratas) < 2) {
            $kategoriTeratas[] = ['name' => null, 'total' => 0];
        }

        return view('siswa.dashboard', compact(
            'featured',
            'portfolios',
            'totalKarya',
            'kategoriTeratas'
        ));
    }

    public function guru(Request $request)
    {
        $categories = Category::all();

        $totalSiswa = User::where('role', 'siswa')->count();
        // Hanya karya milik siswa yang masih aktif (belum diarsipkan/soft-deleted).
        // Karya milik siswa yang diarsipkan tidak ikut soft-delete, sehingga relasi
        // `user` bernilai null dan view akan error 500 jika tidak disaring di sini.
        // Pola yang sama dipakai PublicPortfolioController::show().
        $totalKarya = Portfolio::whereHas('user')->count();

        $portfolios = Portfolio::with(['user', 'category'])
            ->whereHas('user')
            ->latest()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->paginate(12)
            ->withQueryString();

        return view('guru.dashboard', compact(
            'categories',
            'totalSiswa',
            'totalKarya',
            'portfolios'
        ));
    }
}