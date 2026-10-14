# Laravel PHPUnit Lab — Simulasi Checkout

**Bahan ajar Unit Testing, Feature Testing, PHPUnit, boundary value, mocking, CFG dan Independent Path Testing.**

## Status paket
Ini adalah **source project Laravel 13** (bukan `vendor/` siap-pakai). Dependencies pihak ketiga tidak disertakan agar ZIP berukuran kecil, aman dan dapat diinstal pada OS mahasiswa. **Jalankan `composer install` sebelum aplikasi dijalankan**. Tidak memerlukan Node/npm. **Versi 2.0** juga menyediakan migrasi database, model Eloquent, factory, seeder dan pengujian database. Unit Test murni tetap tidak membutuhkan DB.

## Update versi 2.0: migrasi database

**Baca [`PANDUAN_DATABASE.md`](PANDUAN_DATABASE.md) untuk instalasi, skema, migration, seeder, endpoint penyimpanan dan pengujian.** SQLite sudah dikonfigurasi secara default dan file kosong `database/database.sqlite` disertakan.

## Persyaratan
- PHP **8.3+** dan ekstensi yang diwajibkan Laravel: `ctype`, `filter`, `hash`, `mbstring`, `openssl`, `session`, `tokenizer` dan ekstensi umum Laravel/PHPUnit (antara lain `dom`, `xml`, `xmlwriter`).
- Composer 2.x, koneksi internet saat instalasi dependensi.
- Ekstensi `pdo_sqlite` untuk SQLite, atau `pdo_mysql` untuk MySQL. `fakerphp/faker` terpasang melalui Composer untuk factory/seeder.
- Xdebug dengan mode `coverage` **opsional**, khusus laporan branch/path coverage.
- Sistem operasi Windows, Linux atau macOS.

## Instalasi
```bash
# ekstrak ZIP dan buka folder proyek
composer install
cp .env.example .env      # Windows CMD: copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed           # opsional: 12 contoh pesanan
php artisan serve
```
Buka `http://127.0.0.1:8000`. Jika Composer mengingatkan ekstensi PHP yang belum aktif, aktifkan dahulu melalui `php.ini` yang dipakai CLI.

## Jalankan pengujian
```bash
php artisan test
php artisan test --testsuite=Unit
php artisan test --filter=DiskonServiceTest
php artisan test --filter=CheckoutApiTest
php artisan test --filter=CheckoutOrderApiTest
php artisan test --filter=CheckoutOrderWebTest
```

Dengan Xdebug (PowerShell):
```powershell
$env:XDEBUG_MODE="coverage"
php vendor/bin/phpunit --filter=DiskonServiceTest --path-coverage --coverage-html=build/coverage
```
Linux/macOS:
```bash
XDEBUG_MODE=coverage php vendor/bin/phpunit --filter=DiskonServiceTest --path-coverage --coverage-html=build/coverage
```
Laporan: `build/coverage/index.html`. **Tidak boleh menyimpulkan basis path tercapai hanya dari line coverage.**

## Struktur program
```
app/Services/DiskonService.php       # V(G)=3, jalur independen P1-P3
app/Services/OngkirService.php       # Ongkir berdasar net subtotal + ekspres
app/Services/CheckoutService.php     # Orkestrasi, guard clause, dependency injection
app/Http/Requests/CheckoutRequest.php # Validasi input HTTP
app/Http/Controllers/CheckoutController.php
routes/web.php                       # GET / dan POST /checkout
routes/api.php                       # POST /api/checkout
tests/Unit/*                         # PHPUnit tanpa bootstrap Laravel
tests/Feature/CheckoutApiTest.php    # Laravel HTTP feature test
database/migrations/*                 # Tabel checkout_orders
app/Models/CheckoutOrder.php          # Eloquent Model
app/Services/CheckoutOrderService.php # Hasil checkout disimpan
database/factories/* dan seeders/*    # Data contoh
tests/Feature/CheckoutOrder*Test.php  # RefreshDatabase, assertDatabaseHas
```

## API contoh
**POST** `/api/checkout`, JSON:
```json
{"harga":100000,"jumlah":2,"member":true,"ekspres":true}
```
Hasil utama (HTTP 200): subtotal Rp200.000; diskon 10%/Rp20.000; net Rp180.000; ongkir Rp35.000; **total Rp215.000**. Input `harga=0` harus HTTP 422.

## Penyimpanan database (tambahan versi 2.0)

- Tombol **Hitung total** dan `POST /api/checkout`: hanya menghitung, **tanpa INSERT**.
- Tombol **Hitung & Simpan**, `POST /orders`, dan `POST /api/orders`: hitung ulang di server, lalu **INSERT** ke `checkout_orders`.
- `GET /orders`: riwayat pesanan dan pagination. `GET /api/orders/{id}`: mengambil satu pesanan.
- Perintah: `php artisan migrate`, `php artisan db:seed`, lalu `php artisan test`.
- Tes DB menggunakan `RefreshDatabase` dan SQLite memory sehingga tidak mengubah database development.

## Aturan bisnis
- Anggota: +10% diskon.
- Subtotal >= Rp500.000: +5% diskon, dapat terakumulasi hingga 15%.
- Ongkir reguler: Rp20.000 bila nilai setelah diskon < Rp300.000; gratis jika >= Rp300.000.
- Ekspres: tambahan Rp15.000 (termasuk ketika ongkir reguler gratis).
- `harga > 0`, `jumlah > 0`; batas API masing-masing Rp10.000.000 dan 1.000 unit.
- Seluruh perhitungan dalam Rupiah integer (tanpa float).

## Jawaban konsep CFG
Pada `DiskonService::persentase`, dua keputusan `if` menghasilkan V(G)=3. Basis yang sah misalnya P1 (F,F), P2 (T,F), P3 (F,T); P4 (T,T) adalah jalur ekstra agar empat kombinasi tertutup. **V(G)=3 bukan berarti hanya ada 3 jalur total.**

## Keamanan dan batasan
Aplikasi ini untuk *laboratorium lokal*, bukan toko online produksi: tanpa autentikasi, pesanan nyata, pembayaran, rate limit khusus, inventaris, atau fitur produksi. Jangan unggah `.env` ke publik. Gunakan `APP_DEBUG=false` pada produksi. Tidak ada dependensi terkunci (`composer.lock`) sebelum `composer install` pertama kali; setelah instalasi, simpan file lock agar lingkungan praktikum seragam.

## Referensi
- https://laravel.com/framework/docs/13.x/testing
- https://laravel.com/framework/docs/13.x/routing
- https://docs.phpunit.de/en/12.5/
- https://xdebug.org/docs/code_coverage
