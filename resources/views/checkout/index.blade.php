<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel PHPUnit Lab — Simulasi Checkout</title>
    <style>
        :root{color-scheme:light;--bg:#f4f7fb;--navy:#132641;--blue:#1461d2;--muted:#556780;--line:#dce5f2;--green:#0d7a64}
        *{box-sizing:border-box}body{margin:0;font:16px/1.55 system-ui,-apple-system,'Segoe UI',sans-serif;background:var(--bg);color:var(--navy)}
        header{padding:44px 6vw 35px;background:linear-gradient(115deg,#122641,#2255a2);color:white}header .pill{display:inline-block;background:#ffffff24;border:1px solid #ffffff55;border-radius:100px;padding:5px 14px;font-size:12px;letter-spacing:.08em}header h1{margin:12px 0 7px;font-size:clamp(28px,4vw,42px);line-height:1.15}header p{margin:0;max-width:700px;color:#dae7ff}
        main{max-width:1110px;margin:30px auto;padding:0 22px;display:grid;grid-template-columns:1.05fr .95fr;gap:22px}
        .card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:27px;box-shadow:0 9px 28px #10243c0c}
        h2{margin:0 0 19px;font-size:21px}label{display:block;font-size:14px;font-weight:700;margin-bottom:6px}input[type=number]{width:100%;border:1px solid #bccbe0;border-radius:9px;padding:11px 12px;font-size:16px;color:var(--navy)}
        .field{margin:0 0 20px}.checks{display:flex;flex-wrap:wrap;gap:14px 24px;margin:4px 0 22px}.checks label{font-size:14px;font-weight:500;margin:0}.checks input{accent-color:var(--blue)}button{background:var(--blue);border:0;border-radius:9px;color:white;font-weight:700;padding:13px 20px;cursor:pointer;font-size:15px}button:hover{background:#0e4aa6}.secondary{background:#0d7a64;margin-left:8px}.secondary:hover{background:#09604f}.subnav{margin-top:13px}
        .row{display:flex;justify-content:space-between;gap:12px;border-bottom:1px solid var(--line);padding:10px 0;font-size:14px}.row strong{font-variant-numeric:tabular-nums}.total{color:var(--green);font-weight:800;font-size:20px}.note{font-size:13px;color:var(--muted);line-height:1.6;margin-top:15px}code{font-family:Consolas,monospace;background:#edf4ff;padding:2px 5px;border-radius:4px;font-size:13px;word-break:break-word}
        .info{margin-top:24px;border-top:1px solid var(--line);padding-top:16px}.info li{margin-bottom:7px}.errors{background:#fff1f0;color:#9e1b13;border-radius:9px;padding:12px 17px;font-size:14px;margin-bottom:18px}footer{text-align:center;padding:20px;color:#64758a;font-size:13px}
        @media(max-width:790px){main{grid-template-columns:1fr}header{padding:34px 24px}}
    </style>
</head>
<body>
<header>
    <div class="pill">PRAKTIKUM REKAYASA PERANGKAT LUNAK</div>
    <h1>Simulasi Checkout Laravel</h1>
    <p>Eksperimen dengan diskon, ongkos kirim, dan jalur pengujian. Aturan bisnis diuji dengan PHPUnit. Anda juga dapat menyimpan hasilnya ke database untuk latihan migration dan Feature Test.</p>
</header>
<main>
    <section class="card">
        <h2>Masukkan data transaksi</h2>
        @if ($errors->any())
            <div class="errors"><strong>Input belum valid.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form method="post" action="{{ route('checkout.calculate') }}">
            @csrf
            <div class="field"><label for="harga">Harga satuan (Rp)</label><input id="harga" name="harga" type="number" min="1" max="10000000" value="{{ old('harga', 200000) }}" required></div>
            <div class="field"><label for="jumlah">Jumlah barang</label><input id="jumlah" name="jumlah" type="number" min="1" max="1000" value="{{ old('jumlah', 2) }}" required></div>
            <div class="checks">
                <label><input type="checkbox" name="member" value="1" @checked(old('member', false))> Pelanggan anggota</label>
                <label><input type="checkbox" name="ekspres" value="1" @checked(old('ekspres', false))> Kirim ekspres</label>
            </div>
            <button type="submit">Hitung total</button><button class="secondary" type="submit" formaction="{{ route('orders.store') }}">Hitung &amp; Simpan</button>
            <p class="subnav"><a href="{{ route('orders.index') }}">Lihat riwayat pesanan tersimpan →</a></p>
        </form>
        <div class="info">
            <h2>Aturan bisnis yang diuji</h2>
            <ul><li>Anggota mendapat diskon 10%.</li><li>Subtotal minimal Rp500.000 mendapat tambahan diskon 5%.</li><li>Ongkir Rp20.000; gratis jika subtotal setelah diskon minimal Rp300.000.</li><li>Pengiriman ekspres menambah Rp15.000.</li></ul>
        </div>
    </section>
    <aside class="card">
        <h2>Ringkasan hasil</h2>
        @isset($hasil)
            @php($rupiah = fn ($n) => 'Rp'.number_format($n, 0, ',', '.'))
            <div class="row"><span>Subtotal</span><strong>{{ $rupiah($hasil['subtotal']) }}</strong></div>
            <div class="row"><span>Diskon</span><strong>{{ $hasil['persentase_diskon'] }}%</strong></div>
            <div class="row"><span>Potongan</span><strong>-{{ $rupiah($hasil['potongan_diskon']) }}</strong></div>
            <div class="row"><span>Setelah diskon</span><strong>{{ $rupiah($hasil['subtotal_setelah_diskon']) }}</strong></div>
            <div class="row"><span>Ongkos kirim</span><strong>{{ $rupiah($hasil['ongkir']) }}</strong></div>
            <div class="row"><span>TOTAL BAYAR</span><strong class="total">{{ $rupiah($hasil['total']) }}</strong></div>
        @else
            <p style="color:#65748a">Hasil perhitungan tampil di sini setelah formulir dikirim.</p>
        @endisset
        <div class="info">
            <h2>Endpoint API latihan</h2>
            <p><code>POST /api/checkout</code> (menghitung saja)</p><p><code>POST /api/orders</code> (menyimpan ke database)</p><p><code>GET /api/orders/{id}</code> (mengambil pesanan)</p>
            <p class="note">Gunakan Postman atau pengujian Laravel <code>postJson()</code>. Contoh: <code>{"harga":100000,"jumlah":2,"member":true,"ekspres":true}</code></p>
            <p class="note">Perintah pengujian: <code>php artisan test</code></p>
        </div>
    </aside>
</main>
<footer>Laravel PHPUnit Lab · Pembelajaran Unit Testing, Feature Testing &amp; Independent Path</footer>
</body>
</html>
