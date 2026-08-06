<?php
// app/Http/Controllers/StudentController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Tampilkan daftar seluruh akun siswa beserta jumlah karya & prestasi
     * yang sudah mereka unggah. Bisa dicari lewat nama, NIS, atau email,
     * diurutkan alfabetis, dan dipaginasi agar tetap ringan.
     */
    public function index(Request $request): View
    {
        // ?trashed=1 menampilkan akun yang sudah "dihapus" guru (arsip),
        // sehingga guru bisa memulihkannya kalau ternyata terhapus keliru.
        $showTrashed = $request->boolean('trashed');

        $query = $showTrashed
            ? User::onlyTrashed()->where('role', 'siswa')
            : User::where('role', 'siswa');

        $students = $query
            ->withCount(['portfolios', 'achievements'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nis_nip', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalArsip = User::onlyTrashed()->where('role', 'siswa')->count();

        return view('guru.siswa.index', [
            'students'    => $students,
            'totalSiswa'  => $totalSiswa,
            'totalArsip'  => $totalArsip,
            'showTrashed' => $showTrashed,
        ]);
    }

    /**
     * Guru mendaftarkan akun siswa baru secara terpusat. Password diisi
     * langsung oleh guru saat pendaftaran (bukan link aktivasi email)
     * karena siswa SMK umumnya belum punya email pribadi yang aktif dipantau.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'nis_nip'  => ['nullable', 'string', 'max:50', 'unique:users,nis_nip'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.required'     => 'Nama siswa wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => "Email ':input' sudah terdaftar, gunakan email lain.",
            'nis_nip.unique'    => "NIS ':input' sudah dipakai siswa lain, periksa kembali.",
            'password.required' => 'Password awal wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'nis_nip'  => $validated['nis_nip'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'siswa',
        ]);

        return redirect()->route('guru.siswa.index')
            ->with('success', "Akun siswa '{$validated['name']}' berhasil didaftarkan!");
    }

    /**
     * Perbarui data siswa. Password bersifat opsional — kosongkan jika
     * guru tidak ingin mereset password siswa yang bersangkutan. Error bag
     * dipisah per-siswa (editStudent{id}) supaya validasi modal edit pada
     * baris tabel yang berbeda tidak saling tabrakan nama field-nya.
     */
    public function update(Request $request, User $siswa): RedirectResponse
    {
        abort_unless($siswa->role === 'siswa', 404);

        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($siswa->id),
            ],
            'nis_nip'  => [
                'nullable', 'string', 'max:50',
                Rule::unique('users', 'nis_nip')->ignore($siswa->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'name.required'  => 'Nama siswa wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => "Email ':input' sudah terdaftar, gunakan email lain.",
            'nis_nip.unique' => "NIS ':input' sudah dipakai siswa lain, periksa kembali.",
            'password.min'   => 'Password minimal 8 karakter.',
        ]);
        $validator->validateWithBag('editStudent' . $siswa->id);
        $validated = $validator->validated();

        $siswa->name    = $validated['name'];
        $siswa->email   = $validated['email'];
        $siswa->nis_nip = $validated['nis_nip'] ?? null;

        if (!empty($validated['password'])) {
            $siswa->password = Hash::make($validated['password']);
        }

        $siswa->save();

        return redirect()->route('guru.siswa.index')
            ->with('success', "Data siswa '{$siswa->name}' berhasil diperbarui!");
    }

    /**
     * "Hapus" akun siswa — karena model User memakai SoftDeletes, baris ini
     * tidak benar-benar dibuang dari database, hanya ditandai deleted_at.
     * Karya & prestasi siswa TIDAK ikut terhapus (cascadeOnDelete() di
     * migrasi hanya berlaku untuk DELETE fisik, bukan soft delete), dan
     * guru masih bisa memulihkannya lewat tampilan arsip (?trashed=1).
     */
    public function destroy(User $siswa): RedirectResponse
    {
        abort_unless($siswa->role === 'siswa', 404);

        $namaSiswa = $siswa->name;
        $siswa->delete();

        return redirect()->route('guru.siswa.index')
            ->with('success', "Akun siswa '{$namaSiswa}' dipindahkan ke arsip. Karyanya tetap aman dan bisa dipulihkan.");
    }

    /**
     * Pulihkan akun siswa dari arsip. Route model binding pada rute ini
     * memakai ->withTrashed(), karena secara default Laravel tidak akan
     * menemukan baris yang sudah soft-deleted saat melakukan binding.
     */
    public function restore(User $siswa): RedirectResponse
    {
        abort_unless($siswa->role === 'siswa', 404);

        $siswa->restore();

        return redirect()->route('guru.siswa.index')
            ->with('success', "Akun siswa '{$siswa->name}' berhasil dipulihkan.");
    }

    /**
     * Hapus akun siswa secara PERMANEN dari database (mis. akun salah input
     * saat pendaftaran). Hanya bisa dilakukan pada akun yang sudah berstatus
     * arsip (soft-deleted) terlebih dahulu, sebagai lapisan konfirmasi
     * tambahan sebelum data benar-benar tidak bisa dikembalikan lagi —
     * termasuk seluruh portofolio & prestasi terkait, karena forceDelete()
     * memicu DELETE fisik sehingga cascadeOnDelete() di database ikut berjalan.
     */
    public function forceDelete(User $siswa): RedirectResponse
    {
        abort_unless($siswa->role === 'siswa', 404);
        abort_unless($siswa->trashed(), 403, 'Pindahkan akun ke arsip terlebih dahulu sebelum menghapus permanen.');

        $namaSiswa = $siswa->name;
        $siswa->forceDelete();

        return redirect()->route('guru.siswa.index', ['trashed' => 1])
            ->with('success', "Akun siswa '{$namaSiswa}' beserta seluruh datanya dihapus permanen.");
    }
}