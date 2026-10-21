<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\Achievement; // 1. Penambahan model Achievement
use Illuminate\Http\Request;

class PublicPortfolioController extends Controller
{
    public function show($slug)
    {
        // Hanya portfolio dengan owner (User) yang masih ada & aktif
        // (belum soft-deleted) yang boleh ditemukan di sini.
        $portfolio = Portfolio::with(['user', 'category'])
            ->whereHas('user')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPortfolios = Portfolio::with('category')
            ->where('user_id', $portfolio->user_id)
            ->where('id', '!=', $portfolio->id)
            ->latest()
            ->limit(4)
            ->get();
            
        return view('public.portfolio.show', compact('portfolio', 'relatedPortfolios'));
    }

    public function profile($slug)
    {
        $user = User::where('portfolio_slug', $slug)
            ->where('role', 'siswa')
            ->firstOrFail();

        // Daftar karya dipaginasi (12 per halaman) agar profil siswa dengan banyak
        // karya tidak memuat semuanya sekaligus. Angka ringkasan & tagline dihitung
        // lewat query terpisah supaya mencerminkan SEMUA karya, bukan hanya halaman ini.
        $worksOfUser = fn () => Portfolio::where('user_id', $user->id);

        $portfolios = $worksOfUser()
            ->with('category')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString()
            ->fragment('karya');

        $totalKarya = $worksOfUser()->count();
        $totalKategori = $worksOfUser()->whereNotNull('category_id')->distinct()->count('category_id');

        // Kategori terbanyak -> dipakai untuk tagline "Spesialis ..." di profil.
        $kategoriDominan = $worksOfUser()
            ->with('category')
            ->whereNotNull('category_id')
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->orderBy('category_id')
            ->first()
            ?->category
            ?->name;

        // 2. Penambahan query achievements sebelum return view
        $achievements = Achievement::where('user_id', $user->id)
            ->latest('achieved_at')
            ->get();

        // 3. Penambahan 'achievements' ke dalam compact
        return view('public.portfolio.profile', compact('user', 'portfolios', 'totalKarya', 'totalKategori', 'kategoriDominan', 'achievements'));
    }

    // Tampilkan PDF ringkas (1-2 halaman) milik siswa secara publik lewat slug,
    // tanpa perlu login. Memakai template yang sama dengan rute privat
    // siswa.portfolio.print agar hasil cetak selalu konsisten.
    public function print($slug)
    {
        $user = User::where('portfolio_slug', $slug)
            ->where('role', 'siswa')
            ->firstOrFail();

        $portfolios = Portfolio::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('portfolio.print-ringkas', compact('user', 'portfolios'));
    }
}