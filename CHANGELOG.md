# Changelog

## 2.0 — 12 Oktober 2026

- Tambah konfigurasi database SQLite (default) dan MySQL.
- Tambah migration `checkout_orders`, model Eloquent, factory dan seeder 12 record.
- Tambah `CheckoutOrderService` untuk menyimpan hasil kalkulasi ke database.
- Tambah `POST /api/orders`, `GET /api/orders/{id}`, `POST /orders`, dan `GET /orders`.
- Tambah tombol **Hitung & Simpan** dan halaman riwayat pesanan.
- Tambah Feature Test database (migration, CRUD create/read, validasi, dan seeder) dengan `RefreshDatabase`.
- Mempertahankan pengujian serta endpoint lama sehingga studi Unit Testing dan CFG tetap berlaku.
- Tambah `PANDUAN_DATABASE.md` dan `LATIHAN_DATABASE_MAHASISWA.md`.

## 1.0 — 11 Oktober 2026

- Proyek awal simulasi checkout Laravel dengan PHPUnit Unit/Feature Test.
