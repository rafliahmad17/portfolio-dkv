# Sistem Manajemen Portofolio Digital DKV
### SMKN 2 Padang Panjang (SMEKDA)

Aplikasi web untuk manajemen portofolio digital siswa jurusan Desain Komunikasi
Visual (DKV). Siswa mengunggah karya, membangun portofolio publik dengan URL
unik, mencatat skill & kompetensi, serta prestasi/sertifikat. Guru memantau
seluruh karya siswa dan mengelola kategori serta akun siswa.

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Stack Teknologi](#stack-teknologi)
- [Prasyarat](#prasyarat)
- [Instalasi Lokal](#instalasi-lokal)
- [Akun Demo](#akun-demo)
- [Menjalankan Test](#menjalankan-test)
- [Peta Halaman](#peta-halaman)
- [Struktur Folder](#struktur-folder)
- [Pemecahan Masalah](#pemecahan-masalah)
- [Deployment Produksi](#deployment-produksi)
- [Lisensi](#lisensi)

## Fitur Utama

- **Autentikasi & Role**: login dengan tiga peran (`admin`, `guru`, `siswa`).
  Admin dan guru memakai area guru; siswa memakai area siswa. Login dibatasi
  percobaannya per kombinasi email + IP.
- **Lupa & Reset Password**: tautan reset dikirim lewat email.
- **CRUD Portofolio**: siswa mengunggah, mengedit, dan menghapus karya
  (gambar JPG/PNG maks. 2 MB, PDF opsional maks. 5 MB). Isi file diperiksa,
  bukan hanya ekstensinya. Gambar otomatis diperkecil (maks. 1600 px),
  dikonversi ke WebP, dan dibuatkan thumbnail 480 px untuk kartu/galeri.
- **Live URL Portfolio**: halaman profil publik per siswa lewat slug (`/u/{slug}`)
  dan halaman detail karya (`/p/{slug}`). Daftar karya dipaginasi 12 per halaman.
- **PDF Ringkas**: cetak/unduh portofolio ringkas 1-2 halaman, bisa diakses
  publik lewat QR code.
- **Skill & Kompetensi**: siswa mencatat level kemampuan software desain dan
  kompetensi inti.
- **Prestasi & Sertifikat**: dicatat siswa dan tampil di portofolio publik.
- **Kelola Kategori** (guru): tambah, ubah, dan hapus kategori karya.
- **Kelola Data Siswa** (guru): mendaftarkan akun siswa, mengarsipkan,
  memulihkan, dan menghapus permanen (beserta file-nya).
- **Dashboard Guru**: pencarian dan filter seluruh karya siswa.
- **Privasi**: siswa mengatur apakah kontak (WhatsApp) tampil di profil publik.
  Akun siswa yang diarsipkan otomatis hilang dari halaman publik.

## Stack Teknologi

| Lapisan | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Frontend | Blade, Tailwind CSS 4, JavaScript biasa, Vite 8 |
| Database | MySQL / MariaDB (test memakai SQLite in-memory) |
| Bahasa & zona waktu | Bahasa Indonesia (`id`), WIB (`Asia/Jakarta`) |

## Prasyarat

- **PHP 8.3 atau lebih baru**, dengan ekstensi umum Laravel (`mbstring`,
  `openssl`, `pdo_mysql`, `fileinfo`, `ctype`, `tokenizer`, `xml`). Aktifkan
  juga `gd` (dengan dukungan WebP) dan `exif` untuk optimasi gambar; tanpa
  keduanya aplikasi tetap jalan, gambar disimpan apa adanya. Untuk menjalankan
  test, aktifkan `pdo_sqlite`.
- **Composer 2**
- **Node.js 20.19+ atau 22.12+** dan npm (syarat Vite 8)
- **MySQL 8 atau MariaDB 10.x**

Di Windows, paket [Laragon](https://laragon.org) sudah menyediakan semuanya.

## Instalasi Lokal

```bash
# 1. Ambil kode
git clone https://github.com/rafliahmad17/portfolio-dkv.git
cd portfolio-dkv

# 2. Pasang dependensi PHP
composer install

# 3. Siapkan file lingkungan, lalu buat APP_KEY
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
```

**4. Buat database kosong** di MySQL (nama bebas, contoh `db_portfolio_dkv`),
lalu sesuaikan bagian ini di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_portfolio_dkv
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 5. Buat tabel dan isi data contoh (kategori + akun demo + karya dummy)
php artisan migrate --seed

# 6. Hubungkan folder upload agar gambar bisa diakses dari browser
php artisan storage:link

# 7. Pasang dependensi frontend dan bangun asetnya
npm install
npm run build

# 8. Jalankan aplikasi
php artisan serve
```

Buka <http://127.0.0.1:8000>. Selama mengembangkan tampilan, ganti langkah
`npm run build` dengan `npm run dev` (jalankan di terminal terpisah).

> **Jalan pintas:** setelah database dibuat dan `.env` diisi, `composer setup`
> menjalankan `composer install`, `key:generate`, `migrate`, `npm install`, dan
> `npm run build` sekaligus. Anda tetap perlu menjalankan
> `php artisan db:seed` dan `php artisan storage:link` sesudahnya.

**Mengulang dari awal** (menghapus seluruh data lalu mengisi ulang):

```bash
php artisan migrate:fresh --seed
```

**Optimasi gambar.** Karya yang diunggah setelah fitur thumbnail ada sudah
otomatis dioptimasi. Untuk data lama (dan sekali untuk logo sekolah), jalankan:

```bash
php artisan images:optimize --logo
```

Perintah ini membuat thumbnail + dimensi untuk karya lama (gambar utamanya tidak
diubah) dan membuat `logo-sekolah.webp` (256 px) serta `logo-sekolah-64.png`
(favicon) dari logo asli. Aman dijalankan berulang.

Email pada instalasi lokal tidak benar-benar terkirim (`MAIL_MAILER=log`).
Tautan reset password dapat dibaca di `storage/logs/laravel.log`.

## Akun Demo

Dibuat oleh `php artisan db:seed` (hanya di environment non-produksi).
Password semua akun: **`password123`**

| Peran | Email | Masuk ke |
|---|---|---|
| Admin | `admin@smkn2.sch.id` | `/guru/dashboard` |
| Guru | `guru@smkn2.sch.id` | `/guru/dashboard` |
| Siswa | `siswa@smkn2.sch.id` | `/siswa/dashboard` |

Seeder juga membuat 12 siswa lain dengan nama dan email acak (daftarnya ada di
menu **Data Siswa** pada akun guru), masing-masing dengan 3-6 karya dan
beberapa prestasi dummy. Profil publik akun demo ada di `/u/siswa-demo`.

Catatan data demo:

- Karya dan prestasi dummy menunjuk ke berkas gambar/PDF contoh yang **tidak
  disertakan** di repositori, sehingga thumbnail tampil sebagai placeholder.
  Untuk melihat tampilan sebenarnya, masuk sebagai siswa dan unggah karya
  sendiri.
- Seeder karya dan prestasi tidak idempoten. Menjalankannya dua kali menggandakan
  data. Gunakan `migrate:fresh --seed` untuk mengulang.
- Akun demo **diblokir di produksi** (`APP_ENV=production`). Lihat
  [panduan deployment](docs/DEPLOYMENT.md) untuk membuat admin pertama.

## Menjalankan Test

```bash
php artisan test
# atau
composer test
```

Test memakai SQLite in-memory (diatur di `phpunit.xml`), sehingga **tidak
menyentuh database MySQL Anda** dan tidak perlu `migrate` atau `seed` lebih
dulu. Cakupannya meliputi CRUD portofolio/prestasi/kategori/siswa, otorisasi
per peran, kepemilikan data, privasi halaman publik, validasi isi file unggahan,
keamanan password, pembatasan login, paginasi, dan lokalisasi.

## Peta Halaman

| URL | Akses | Fungsi |
|---|---|---|
| `/` | Publik | Beranda (pengguna yang sudah login diarahkan ke dashboard) |
| `/login`, `/forgot-password` | Tamu | Masuk dan reset password |
| `/u/{slug}` | Publik | Profil dan portofolio siswa |
| `/u/{slug}/print` | Publik | PDF ringkas siswa (tujuan QR code) |
| `/p/{slug}` | Publik | Detail satu karya |
| `/siswa/dashboard` | Siswa | Arsip karya, statistik, prestasi, profil |
| `/guru/dashboard` | Admin, guru | Pantau seluruh karya, cari dan filter |
| `/guru/kategori` | Admin, guru | Kelola kategori |
| `/guru/siswa` | Admin, guru | Kelola akun siswa |

## Struktur Folder

```text
app/Http/Controllers/   Pengendali (Auth, Dashboard, Portfolio, Student, ...)
app/Http/Middleware/    RoleMiddleware (pembatas akses per peran)
app/Models/             User, Portfolio, Category, Achievement
database/migrations/    Skema database
database/seeders/       Data referensi dan data demo
lang/id/                Terjemahan pesan validasi, auth, dan paginasi
resources/views/        Templat Blade (auth, guru, siswa, public, errors)
routes/web.php          Seluruh rute aplikasi
tests/Feature/          Test fitur
docs/DEPLOYMENT.md      Panduan deployment produksi
```

## Pemecahan Masalah

| Gejala | Penyebab dan solusi |
|---|---|
| `Vite manifest not found` | Aset frontend belum dibangun. Jalankan `npm run build`. |
| Gambar karya tidak tampil | `php artisan storage:link` belum dijalankan. Di Windows, jalankan terminal sebagai Administrator atau aktifkan Developer Mode. |
| `SQLSTATE[HY000] [2002]` atau `Unknown database` | MySQL belum menyala, atau database belum dibuat. Periksa nilai `DB_*` di `.env`. |
| `Table 'sessions' doesn't exist` | Migrasi belum dijalankan. Jalankan `php artisan migrate`. |
| `No application encryption key` | Jalankan `php artisan key:generate`. |
| Perubahan `.env` tidak terbaca | Jalankan `php artisan config:clear`. |
| `could not find driver` saat test | Aktifkan ekstensi `pdo_sqlite` di `php.ini`. |
| `composer dev` gagal di Windows | Skrip itu memakai `php artisan pail` yang butuh ekstensi `pcntl` (tidak ada di Windows). Jalankan `php artisan serve` dan `npm run dev` di dua terminal. |

## Deployment Produksi

Lihat [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md): konfigurasi `.env` produksi,
langkah instalasi, pembuatan admin pertama, dan pengujian email.

## Lisensi

Dirilis di bawah [Lisensi MIT](LICENSE).
