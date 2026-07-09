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
        $portfolio = Portfolio::with(['user', 'category'])
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

        $portfolios = Portfolio::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalKarya = $portfolios->count();
        $totalKategori = $portfolios->pluck('category_id')->unique()->count();

        // 2. Penambahan query achievements sebelum return view
        $achievements = Achievement::where('user_id', $user->id)
            ->latest('achieved_at')
            ->get();

        // 3. Penambahan 'achievements' ke dalam compact
        return view('public.portfolio.profile', compact('user', 'portfolios', 'totalKarya', 'totalKategori', 'achievements'));
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