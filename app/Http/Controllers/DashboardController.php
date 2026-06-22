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
        $portfolios = Portfolio::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $totalKarya = $portfolios->count();

        $categoryPoster = Category::where('slug', 'poster-banner')->first();
        $categoryUIUX   = Category::where('slug', 'ui-ux-design')->first();

        $totalPoster = $categoryPoster
            ? Portfolio::where('user_id', Auth::id())
                       ->where('category_id', $categoryPoster->id)
                       ->count()
            : 0;

        $totalUIUX = $categoryUIUX
            ? Portfolio::where('user_id', Auth::id())
                       ->where('category_id', $categoryUIUX->id)
                       ->count()
            : 0;

        return view('siswa.dashboard', compact(
            'portfolios',
            'totalKarya',
            'totalPoster',
            'totalUIUX'
        ));
    }

    public function guru(Request $request)
    {
        $categories = Category::all();

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalKarya = Portfolio::count();

        $portfolios = Portfolio::with(['user', 'category'])
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