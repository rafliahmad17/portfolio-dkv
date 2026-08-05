# Sistem Manajemen Portofolio Digital DKV
### SMKN 2 Padang Panjang (SMEKDA)

Aplikasi web untuk manajemen portofolio digital siswa jurusan Desain Komunikasi
Visual (DKV) — siswa dapat mengunggah karya, membangun portofolio publik dengan
URL unik, mencatat skill & kompetensi, serta prestasi/sertifikat. Guru dapat
memantau seluruh karya siswa dan mengelola kategori portofolio.

## Fitur Utama

- **Autentikasi & Role** — login dengan dua peran: guru/admin dan siswa
- **CRUD Portofolio** — siswa mengunggah, mengedit, dan menghapus karya (gambar + PDF opsional)
- **Live URL Portfolio** — halaman portofolio publik per siswa via slug (`/u/{slug}`)
- **PDF Ringkas** — cetak/unduh portofolio ringkas 1-2 halaman, bisa diakses publik lewat QR code
- **Skill & Kompetensi** — siswa mencatat level kemampuan software desain & kompetensi inti
- **Prestasi & Sertifikat** — siswa mencatat prestasi dan sertifikat, tampil di portofolio publik
- **Kelola Kategori** (guru) — guru mengelola kategori portofolio
- **Dashboard Guru** — pencarian & filter seluruh karya siswa

## Stack Teknologi

- **Backend:** Laravel 13, PHP
- **Frontend:** Blade, Tailwind CSS, Vanilla JS, Vite
- **Database:** MySQL

## Instalasi Lokal

```bash
git clone https://github.com/rafliahmad17/portfolio-dkv.git
cd portfolio-dkv

composer install
cp .env.example .env
php artisan key:generate
```

Buka `.env`, sesuaikan koneksi database ke MySQL: