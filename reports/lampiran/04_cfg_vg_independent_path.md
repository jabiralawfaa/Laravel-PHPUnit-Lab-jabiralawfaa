LAMPIRAN 04 — CFG, perhitungan V(G), basis independent path, dan pemetaan terhadap test case
Proyek   : laravel-phpunit-lab
Metode   : white box testing (modul PRAKTIKUM 5)
Catatan  : Gambar CFG resmi ada di modul (Gambar 1 dan Gambar 2). Lampiran ini adalah
           representasi teks + perhitungan + pemetaan test, agar laporan dapat ditulis ulang.

================================================================
1. KASUS UTAMA — DiskonService::persentase(bool $member, int $subtotal)
================================================================

----- 1.1 Control Flow Graph (teks; N=6, E=7) -----

        [N1] diskon = 0
         |
         v
      <N2> if ($member) ............................. predicate 1
       /     \
     T /       \ F
     v           v
 [N3]          <N4> if ($subtotal >= 500000) ....... predicate 2
 diskon+=10   /     \
     |      T /       \ F
     |      v           v
     |   [N5]           |
     |   diskon+=5      |
     |      |           |
     \------|-----------/
            v
        [N6] return diskon

Daftar edge (7):
  E1: N1 → N2
  E2: N2 → N3   (member = true)
  E3: N2 → N4   (member = false)
  E4: N3 → N4
  E5: N4 → N5   (subtotal >= 500000)
  E6: N4 → N6   (subtotal <  500000)
  E7: N5 → N6

----- 1.2 Perhitungan V(G) -----
V(G) = E − N + 2 = 7 − 6 + 2 = 3
Silang-verifikasi dengan jumlah predicate: V(G) = P + 1 = 2 + 1 = 3  (konsisten)
=> Ukuran basis jalur independen = 3 jalur.

----- 1.3 Pemilihan basis jalur independen -----
Basis (pilihan sah, masing-masing memperkenalkan keputusan/edge baru):
  P1 (F,F) : N1→N2→N4→N6         memperkenalkan E3 dan E6
  P2 (T,F) : N1→N2→N3→N4→N6     memperkenalkan E2 (melalui E4, E6)
  P3 (F,T) : N1→N2→N4→N5→N6     memperkenalkan E5 dan E7
Jalur tambahan (di luar basis, melengkapi path coverage 4 dari 4 kombinasi):
  P4 (T,T) : N1→N2→N3→N4→N5→N6 menutup kombinasi keempat; tidak memperluas basis
             karena setiap edge-nya sudah dicakup P1-P3.

----- 1.4 Pemetaan jalur -> test case (bukti eksekusi nyata) -----
Semua test PASS pada run 14-Oct-2026 (lihat Lampiran 01).

| Jalur | Kondisi (member, subtotal) | Expected | Test PHPUnit (data set)                          | Hasil | Referensi |
|-------|----------------------------|----------|--------------------------------------------------|-------|-----------|
| P1    | false, 100.000             | 0%       | DiskonServiceTest::test_semua_kombinasi_jalur    | PASS  | D.2-D.3   |
|       |                            |          |   (P1_nonmember_rendah)                          |       |           |
| P2    | true,  100.000             | 10%      | DiskonServiceTest::test_semua_kombinasi_jalur    | PASS  | D.3       |
|       |                            |          |   (P2_member_rendah)                             |       |           |
| P3    | false, 600.000             | 5%       | DiskonServiceTest::test_semua_kombinasi_jalur    | PASS  | D.3       |
|       |                            |          |   (P3_nonmember_tinggi)                          |       |           |
| P4    | true,  600.000             | 15%      | DiskonServiceTest::test_semua_kombinasi_jalur    | PASS  | D.2 baris |
|       |                            |          |   (P4_member_tinggi)                             |       | 1; D.3    |

Test boundary di luar keempat jalur di atas (wajib; lihat Lampiran 02):
  B1 false, 499.999 -> 0%  : DiskonServiceTest::test_batas_499999_belum_memperoleh_diskon_nominal  PASS
  B2 false, 500.000 -> 5%  : DiskonServiceTest::test_batas_500000_sudah_memperoleh_diskon_nominal PASS (D.2 baris 2)
  BUKTI: saat mutasi >= -> > (Lampiran 02), P1-P4 tetap PASS; hanya B2 yang gagal.
  Kesimpulan: basis jalur tanpa test boundary tidak menangkap off-by-one pada ambang.

================================================================
2. KASUS KEDUA — CheckoutService::hitung() (guard clause, modul Gambar 2)
================================================================

----- 2.1 CFG ringkas validasi input (N=7, E=8) -----
  [start] -> <harga <= 0?> --(true: lempar InvalidArgumentException "Harga...")--> [end]
                                  |
                                 false
                                  v
                            <jumlah <= 0?> --(true: lempar InvalidArgumentException "Jumlah...")--> [end]
                                  |
                                 false
                                  v
                            [hitung subtotal, diskon, ongkir] -> [return array] -> [end]
  Catatan struktural: pengecekan jumlah tidak pernah tercapai bila harga sudah invalid
  (method sudah melempar exception lebih dulu).

----- 2.2 Perhitungan V(G) -----
V(G) = E − N + 2 = 8 − 7 + 2 = 3  => 3 jalur independen: X1, X2, X3.

----- 2.3 Pemetaan jalur -> test case (bukti eksekusi nyata, semua PASS) -----

| Jalur | Input (harga, jumlah)      | Expected                                | Test PHPUnit                                | Hasil |
|-------|----------------------------|-----------------------------------------|---------------------------------------------|-------|
| X1    | 0, 1                       | InvalidArgumentException "Harga harus..." | CheckoutServiceTest::test_harga_nol_ditolak | PASS  |
| X2    | 10000, 0                   | InvalidArgumentException "Jumlah harus..." | CheckoutServiceTest::test_jumlah_nol_ditolak | PASS |
| X3    | 100000, 2 (member, ekspres) | array checkout; total = 215.000       | CheckoutServiceTest::test_hasil_checkout_  | PASS  |
|       | true, true                 | (ongkir 35.000 = 20.000 + 15.000)       |   dengan_dependensi_asli (D.2 baris 4 s/d   |       |
|       |                            |                                         |   5 via endpoint HTTP juga)                 |       |

================================================================
3. CATATAN PENUTUP
================================================================
- Coverage (path/branch) memerlukan Xdebug/PCOV di komputer mahasiswa (modul 5.4);
  angka coverage BUKAN bagian lampiran ini — jalankan sendiri lalu lampirkan
  build/coverage/index.html atau keluaran terminal sesuai mesin masing-masing.
- Pemetaan di atas memakai nama test dan hasil run nyata 14-Oct-2026:
  total suite 29 tests / 79 assertions, seluruhnya PASS (Lampiran 01).
