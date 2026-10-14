# Tugas Tambahan — Praktikum Migration dan Database Feature Testing

## Tujuan
Mahasiswa mampu membedakan unit test murni, HTTP feature test, migration test, dan integration test yang memakai database.

## Tugas

1. Jalankan `php artisan migrate:status`, ambil tangkapan layar hasilnya, dan jelaskan fungsi metode `up()` dan `down()`.
2. Jalankan `php artisan db:seed`, buktikan tabel `checkout_orders` berisi 12 record (sebelum membuat pesanan lain).
3. Kirim `POST /api/checkout` dengan `{ "harga": 100000, "jumlah": 2 }`. Pastikan tabel tidak bertambah.
4. Kirim `POST /api/orders` dengan `{ "harga": 300000, "jumlah": 2, "member": true, "ekspres": false, "total": 1 }`. Jelaskan mengapa `total` tersimpan sebagai 510000, bukan 1.
5. Analisis `tests/Feature/CheckoutOrderApiTest.php`: tandai bagian Arrange, Act dan Assert.
6. Buat satu Feature Test baru yang membuktikan dua pesanan valid menambah jumlah record menjadi 2 dalam satu test.
7. Buat test input `jumlah=0` yang menuntut status HTTP 422 serta tidak ada record di database.
8. Buat test pemanggilan `GET /api/orders/999999` yang mengharapkan 404.
9. Jelaskan alasan `RefreshDatabase` diperlukan dan alasan database `:memory:` lebih aman untuk test.
10. **Tugas analitis:** Bandingkan jalur CFG `CheckoutService::hitung` dengan alur HTTP `CheckoutOrderController::storeApi`. Apakah coverage salah satunya membuktikan yang lain? Jelaskan.

## Bukti yang dikumpulkan

- Screenshot `migrate:status`, `db:seed`, dan hasil tes.
- File test yang ditambahkan, lulus pada `php artisan test`.
- Tabel kasus uji (ID, input, expected result, actual result, PASS/FAIL).
- Penjelasan kapan menggunakan `assertSame`, `expectException`, `assertDatabaseHas`, dan `assertJsonPath`.

## Rubrik tambahan (100 poin)

| Aspek | Poin |
|---|---:|
| Migrasi/struktur database | 20 |
| Pemahaman model/factory/seeder | 15 |
| Rancangan skenario valid/invalid | 20 |
| Kualitas test PHPUnit | 25 |
| Isolasi DB dan dokumentasi bukti | 20 |

> Catatan: jangan memakai database produksi untuk tugas migrasi/tes.
