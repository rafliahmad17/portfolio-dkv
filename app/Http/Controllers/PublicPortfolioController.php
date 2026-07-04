<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PublicPortfolioController extends Controller
{
    public function show($slug)
    {
        // 1. Ganti firstOrFail() dengan first() agar tidak langsung error 404
        $user = User::where('portfolio_slug', $slug)->first();

        // 2. Jika user kosong, tampilkan pesan agar kita tahu apa masalahnya
        if (!$user) {
            return "Debug: Siswa dengan slug '" . $slug . "' TIDAK DITEMUKAN di database. Tolong cek tabel users Anda.";
        }

        // 3. Jika ketemu, baru jalankan kodenya
        $portfolios = Portfolio::where('user_id', $user->id)
                                ->latest()
                                ->get();

        return view('public.portfolio.show', compact('user', 'portfolios'));
    }
}