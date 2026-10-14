# Praktikum database Laravel 13 + PHPUnit

Versi ini merupakan **pengembangan proyek praktikum sebelumnya**, bukan proyek baru. Endpoint kalkulator lama tetap ada dan *tidak menyimpan* data. Simulasi pesanan baru menggunakan tabel `checkout_orders`.

## Persyaratan

- PHP >= 8.3, Composer 2.x.
- Ekstensi **PDO SQLite (`pdo_sqlite`)** untuk SQLite, atau **PDO MySQL (`pdo_mysql`)** jika menggunakan MySQL.
- Ekstensi standar Laravel/PHPUnit, termasuk `mbstring`, `xml`, `dom`, dan lain-lain.
- Seluruh dependensi diinstal melalui `composer install` (folder `vendor/` tidak disertakan).

## 1. Instalasi default SQLite

Buka terminal di dalam folder `laravel-phpunit-lab`, lalu jalankan:

```bash
composer install
# Windows CMD: copy .env.example .env
# Linux/macOS: cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

Catatan: salin `.env.example` menjadi `.env` sebelum menjalankan `key:generate`. File kosong `database/database.sqlite` sudah ada di ZIP, dan `.env.example` secara default menggunakan `DB_CONNECTION=sqlite`.

Buka `http://127.0.0.1:8000`. Form menampilkan dua tombol:

- **Hitung total**: hanya hitung, tidak menyimpan.
- **Hitung & Simpan**: simpan hasil yang dihitung ulang oleh server.

Lihat daftar pesanan di `http://127.0.0.1:8000/orders`.

## 2. Alternatif MySQL / MariaDB

Buat database `laravel_phpunit_lab` melalui MySQL/phpMyAdmin. Edit `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_phpunit_lab
DB_USERNAME=root
DB_PASSWORD=
```

Kemudian jalankan:

```bash
php artisan config:clear
php artisan migrate
php artisan db:seed
```

Jika memakai MySQL melalui XAMPP, aktifkan ekstensi `pdo_mysql` di php.ini yang digunakan oleh **PHP CLI**. Konfigurasi pengguna dan sandi mengikuti setup masing-masing.

## 3. Struktur database

Migrasi: `database/migrations/2026_10_12_000001_create_checkout_orders_table.php`

Tabel `checkout_orders` menyimpan snapshot hasil checkout (semuanya Rupiah integer):

| Kolom | Keterangan |
|---|---|
| `id` | Primary key |
| `harga`, `jumlah` | Input tervalidasi |
| `member`, `ekspres` | Flag boolean |
| `subtotal`, `persentase_diskon` | Nilai sebelum diskon dan persentase |
| `potongan_diskon`, `subtotal_setelah_diskon` | Hasil perhitungan diskon |
| `ongkir`, `total` | Biaya pengiriman dan total bayar |
| `created_at`, `updated_at` | Timestamp Laravel |

Model Eloquent: `app/Models/CheckoutOrder.php`.

Penyimpanan: `app/Services/CheckoutOrderService.php`. Hasil (`subtotal`, `total`, diskon, ongkir) **tidak boleh dipercaya dari input client**; dihitung ulang dari input `harga`, `jumlah`, `member`, dan `ekspres` yang divalidasi.

## 4. Endpoint untuk praktikum

| Method | URL | Perilaku |
|---|---|---|
| GET | `/` | Form checkout |
| POST | `/checkout` | Menghitung tanpa menyimpan (web) |
| POST | `/api/checkout` | Menghitung tanpa menyimpan (API) |
| POST | `/orders` | Simpan pesanan (web, dengan CSRF) |
| GET | `/orders` | Riwayat pesanan (web) |
| POST | `/api/orders` | Simpan pesanan, respons 201 |
| GET | `/api/orders/{id}` | Menampilkan satu pesanan, respons 200/404 |

Contoh kirim JSON ke `POST /api/orders`:

```json
{"harga":300000,"jumlah":2,"member":true,"ekspres":false}
```

Hasil hitung: subtotal 600000, diskon 15%, potongan 90000, ongkir 0, total 510000. Hasil ini juga tersimpan di database.

## 5. Migration, rollback, dan seeding

```bash
php artisan migrate:status
php artisan migrate
php artisan migrate:rollback --step=1
php artisan migrate
php artisan db:seed
```

**PERINGATAN:** `migrate:rollback` menghapus tabel hasil migrasi yang dibatalkan beserta isinya. Perintah `migrate:fresh` menghancurkan seluruh tabel pada koneksi target. Gunakan hanya pada database lab dengan backup, jangan pada database produksi.

## 6. PHPUnit Database Feature Test

```bash
php artisan test --filter=CheckoutOrderApiTest
php artisan test --filter=CheckoutOrderWebTest
php artisan test --filter=MigrationSchemaTest
php artisan test
```

Contoh pengujian database:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutOrderApiTest extends Tests\TestCase
{
    use RefreshDatabase;

    // $this->postJson('/api/orders', [...])->assertCreated();
    // $this->assertDatabaseHas('checkout_orders', ['total' => 510000]);
    // $this->assertDatabaseCount('checkout_orders', 1);
}
```

`RefreshDatabase` dipakai bersama konfigurasi testing **SQLite `:memory:`** di `phpunit.xml`. Dengan demikian, pengujian tidak mengubah database SQLite/MySQL development. Jaga `phpunit.xml` dan environment test agar tidak menunjuk ke database produksi.

Untuk memeriksa koneksi dan konfigurasi test:

```bash
php -m
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

`--testsuite=Unit` menjalankan pengujian service murni tanpa database; `--testsuite=Feature` menjalankan HTTP dan database testing.

## 7. Alur pembelajaran yang disarankan

1. Identifikasi `CheckoutService` sebagai unit murni.
2. Buat CFG `DiskonService::persentase` dan hitung V(G).
3. Jalankan Unit Test tanpa DB.
4. Baca migration `checkout_orders`; jalankan `migrate` dan `db:seed`.
5. Jalankan `POST /api/orders` dan cek data melalui halaman riwayat/database.
6. Jalankan Feature Test `RefreshDatabase`, `assertDatabaseHas` dan `assertDatabaseCount`.
7. Kirim payload total palsu dan buktikan total di database berasal dari perhitungan server.
8. Uji `422` untuk input tidak valid dan `404` untuk pesanan tidak ada.

## 8. Batasan lab

Ini aplikasi edukasi **tanpa login, otorisasi, kontrol stok, payment gateway, kepemilikan pesanan, audit transaksi, atau rate limiting khusus**. Jangan deploy sebagai toko online produksi. Endpoint `GET /api/orders/{id}` terbuka untuk demonstrasi Feature Testing saja.

Sumber: https://laravel.com/framework/docs/13.x/migrations dan https://laravel.com/framework/docs/13.x/database-testing
