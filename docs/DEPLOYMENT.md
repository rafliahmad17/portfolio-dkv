# Panduan Deployment Produksi — Portfolio DKV SMEKDA

## 1. Persiapan server
- PHP sesuai `composer.json`, MySQL/MariaDB, ekstensi PHP standar Laravel.
- Document root web server diarahkan ke folder `public/`, bukan ke root project.
- Situs wajib diakses lewat HTTPS.

## 2. File `.env`
```
cp .env.production.example .env
php artisan key:generate
```
Lalu edit `.env` dan ganti semua nilai bertanda `GANTI`. Pastikan:

| Variabel | Nilai produksi |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | URL HTTPS publik |
| `LOG_LEVEL` | `error` |
| `SESSION_SECURE_COOKIE` | `true` |
| `MAIL_MAILER` | `smtp` (bukan `log`/`array`) |
| `DB_USERNAME` | bukan `root` |

`.env` tidak boleh di-commit ke Git.

## 3. Instalasi
```
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 4. Seeder di produksi
**Jangan** menjalankan `UserSeeder`, `PortfolioSeeder`, atau `AchievementSeeder`.
`UserSeeder` membuat akun admin/guru/siswa dengan password `password123`.

Kode sudah memblokir seeder tersebut saat `APP_ENV=production`.
`php artisan db:seed` di produksi hanya mengisi kategori (`CategorySeeder`).

## 5. Membuat akun admin pertama
Karena seeder user diblokir, buat admin lewat tinker dengan password kuat
(minimal 12 karakter, tidak dipakai di tempat lain):

```
php artisan tinker
>>> $u = new App\Models\User;
>>> $u->name = 'Nama Admin';
>>> $u->email = 'email-admin@domain-sekolah.sch.id';
>>> $u->role = 'admin';
>>> $u->password = 'GANTI-password-kuat';
>>> $u->email_verified_at = now();
>>> $u->save();
```
Password otomatis di-hash oleh cast `hashed` pada model `User`.
Setelah login, siswa dan guru lain didaftarkan lewat menu Data Siswa.

## 6. Uji email sebelum dipakai
Buka `/forgot-password` dengan email akun nyata dan pastikan email masuk.
Jika tidak masuk, periksa `storage/logs/` dan kredensial SMTP.

## 7. Setelah deploy
- Kunjungi satu URL yang tidak ada: harus tampil halaman 404 biasa, bukan
  halaman debug Laravel.
- Jalankan ulang `php artisan config:cache` setiap kali `.env` berubah.
- Cadangkan database dan folder `storage/app/public` secara berkala.
