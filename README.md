# RuangKita — Mini Project Booking Meeting Room

Aplikasi Laravel untuk pencarian ruang, permintaan booking, approval admin, kalender ketersediaan, dan ekspor laporan CSV.

## Prasyarat

PHP 8.2+, Composer, Node.js/NPM, dan SQLite (default) atau MySQL. Aplikasi menggunakan Laravel 12, Blade, Tailwind CSS, dan Vite. Zona waktu aplikasi adalah `Asia/Jakarta`.

## Instalasi Windows / Laragon

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File database\database.sqlite -Force
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Untuk MySQL, ubah `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada `.env`, buat database lebih dulu, kemudian jalankan migrasi. Pastikan PHP CLI yang dipakai Laragon/XAMPP memiliki ekstensi `pdo_mysql`, `mbstring`, dan `xml`.

## Akun demo lokal

- Admin: `admin@ruangkita.test` / `password`
- Employee: `budi@ruangkita.test` / `password`

Gunakan akun ini hanya untuk lingkungan lokal. Seeder tidak berjalan otomatis di production.

## Fitur

- Role employee/admin dengan middleware backend, bukan hanya penyembunyian UI.
- CRUD ruang dan fasilitas, termasuk validasi unggahan gambar.
- Booking dengan validasi kapasitas, jam operasional, durasi, waktu lampau, dan konflik rentang waktu.
- Transaksi dan row locking untuk pemeriksaan ulang saat membuat/menyetujui booking. MySQL tidak menyediakan constraint unik native untuk rentang waktu, sehingga validasi transaksi ini tetap diperlukan.
- Approval/penolakan, riwayat status, pembatalan, daftar dan kalender ketersediaan yang tidak membocorkan judul booking pengguna lain.
- Pencarian/pagination ruang, laporan terfilter dan CSV dengan mitigasi formula injection.

## Pengujian

```powershell
php artisan test
```

Tes menggunakan database testing terpisah sesuai konfigurasi `phpunit.xml`. Jalankan `php artisan storage:link` apabila fitur gambar ruangan digunakan.
