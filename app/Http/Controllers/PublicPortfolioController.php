<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use Illuminate\View\View;

class PublicPortfolioController extends Controller
{
    public function show($slug): View
    {
        // 1. Cari data siswa berdasarkan portfolio_slug
        $user = User::where('portfolio_slug', $slug)
                    ->where('role', 'siswa')
                    ->firstOrFail();

        // 2. Ambil semua karya milik siswa tersebut beserta kategorinya
        $portfolios = Portfolio::with('category')
                             ->where('user_id', $user->id)
                             ->latest()
                             ->get();

        // 3. Kirim data ke tampilan halaman publik
        return view('public.portfolio.show', compact('user', 'portfolios'));
    }
}
