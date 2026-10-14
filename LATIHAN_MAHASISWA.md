# Tugas Praktikum: PHPUnit Laravel

1. **AAA:** Jelaskan bagian Arrange, Act, Assert pada `DiskonServiceTest::test_semua_kombinasi_jalur`.
2. **CFG:** Buat CFG untuk `DiskonService::persentase`; hitung E, N, V(G). Sebutkan tiga independent path dan bukti test-nya.
3. **Boundary:** Tambahkan satu test baru untuk subtotal Rp499.999 dan Rp500.000 sebagai anggota. Prediksi diskonnya sebelum menjalankan test.
4. **Ongkir:** Gambarkan CFG `OngkirService::hitung` dan identifikasi 4 kombinasi eksekusi.
5. **Mock:** Jelaskan alasan `CheckoutServiceTest::test_mock_mengisolasi_dependensi` tidak disebut feature test.
6. **Validation:** Tambahkan feature test untuk `jumlah=-1` menghasilkan HTTP 422.
7. **TDD:** Tuliskan test yang gagal untuk kebijakan baru: pembelian >= 3 barang memperoleh bonus gratis kemasan (mis. key `bonus_kemasan` bernilai true); ubah kode hingga hijau, lalu refactor.
8. **Mutasi:** Ubah sementara operator `>= 500000` menjadi `> 500000`; tunjukkan test batas yang gagal. Kembalikan kode semula.

**Dikumpulkan:** screenshot terminal PASS/FAIL saat mutasi, source test baru, tabel independent path, gambar CFG, laporan coverage (jika Xdebug tersedia), dan refleksi maksimal 1 halaman. Jangan mengirim folder `vendor`.
