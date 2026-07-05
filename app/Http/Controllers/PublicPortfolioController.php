<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PublicPortfolioController extends Controller
{
    public function show($slug)
    {
        // Cari portfolio by slug, eager load relasi user & category
        $portfolio = Portfolio::with(['user', 'category'])
            ->where('slug', $slug)
            ->firstOrFail(); // Auto 404 jika tidak ditemukan


        // Ambil karya lain dari siswa yang sama (related works)
        $relatedPortfolios = Portfolio::with('category')
            ->where('user_id', $portfolio->user_id)
            ->where('id', '!=', $portfolio->id)
            ->latest()
            ->limit(4)
            ->get();
        return view('public.portfolio.show', compact('portfolio', 'relatedPortfolios'));
    }
}