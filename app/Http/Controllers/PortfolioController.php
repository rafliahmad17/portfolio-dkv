<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function create(): View
    {
        $categories = Category::all();
        return view('siswa.portfolio.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'category_id'   => ['required', 'exists:categories,id'],
            'description'   => ['required', 'string'],
            'image'         => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'file_pdf'      => ['nullable', 'mimes:pdf', 'max:5120'],
        ]);

        $imagePath = $request->file('image')->store('portfolios/images', 'public');

        $pdfPath = null;
        if ($request->hasFile('file_pdf')) {
            $pdfPath = $request->file('file_pdf')->store('portfolios/pdf', 'public');
        }

        Portfolio::create([
            'title'         => $validated['title'],
            'slug'          => Str::slug($validated['title']) . '-' . Str::random(6),
            'description'   => $validated['description'],
            'category_id'   => $validated['category_id'],
            'user_id'       => Auth::id(),
            'image_path'    => $imagePath,
            'file_pdf_path' => $pdfPath,
        ]);

        return redirect()->route('siswa.dashboard')
                         ->with('success', 'Karya berhasil diunggah! 🎨');
    }

    public function edit(Portfolio $portfolio): View
    {
        abort_if($portfolio->user_id !== Auth::id(), 403);

        $categories = Category::all();
        return view('siswa.portfolio.edit', compact('portfolio', 'categories'));
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        abort_if($portfolio->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'file_pdf'    => ['nullable', 'mimes:pdf', 'max:5120'],
        ]);

        $imagePath = $portfolio->image_path;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($portfolio->image_path);
            $imagePath = $request->file('image')->store('portfolios/images', 'public');
        }

        $pdfPath = $portfolio->file_pdf_path;
        if ($request->hasFile('file_pdf')) {
            if ($portfolio->file_pdf_path) {
                Storage::disk('public')->delete($portfolio->file_pdf_path);
            }
            $pdfPath = $request->file('file_pdf')->store('portfolios/pdf', 'public');
        }

        $portfolio->update([
            'title'         => $validated['title'],
            'slug'          => Str::slug($validated['title']) . '-' . Str::random(6),
            'description'   => $validated['description'],
            'category_id'   => $validated['category_id'],
            'image_path'    => $imagePath,
            'file_pdf_path' => $pdfPath,
        ]);

        return redirect()->route('siswa.dashboard')
                         ->with('success', 'Karya berhasil diperbarui! ✏️');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        abort_if($portfolio->user_id !== Auth::id(), 403);

        Storage::disk('public')->delete($portfolio->image_path);

        if ($portfolio->file_pdf_path) {
            Storage::disk('public')->delete($portfolio->file_pdf_path);
        }

        $portfolio->delete();

        return redirect()->route('siswa.dashboard')
                         ->with('success', 'Karya berhasil dihapus.');
    }

   public function printView(): View
    {
        $user       = Auth::user();
        $portfolios = Portfolio::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('siswa.portfolio.print', compact('portfolios', 'user'));
    }
}