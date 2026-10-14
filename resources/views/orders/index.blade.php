<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Riwayat Pesanan — Laravel PHPUnit Lab</title>
<style>
body{background:#f4f7fb;color:#132641;font:15px/1.6 system-ui,-apple-system,Segoe UI,sans-serif;margin:0}header{background:#132641;color:white;padding:30px max(24px,calc((100vw - 1080px)/2))}h1{margin:0}main{max-width:1080px;margin:30px auto;padding:0 20px}.card{background:white;border:1px solid #dce5f2;border-radius:14px;padding:24px;overflow-x:auto}a{color:#135cc4}.notice{background:#e4f8eb;color:#165b36;padding:12px 17px;border-radius:8px;margin:16px 0}table{width:100%;border-collapse:collapse;white-space:nowrap;text-align:left}th,td{padding:12px;border-bottom:1px solid #e3ebf3}th{background:#f2f6fd}td.num{text-align:right;font-variant-numeric:tabular-nums}small{color:#52637c}.nav{margin-bottom:16px}.pagination{margin-top:18px}footer{color:#64758a;padding:25px;text-align:center}
</style>
</head>
<body>
<header><h1>Riwayat Pesanan</h1><div>Data persisten tersimpan pada tabel <code>checkout_orders</code>.</div></header>
<main>
<div class="nav"><a href="{{ route('checkout.index') }}">← Kembali ke simulasi checkout</a></div>
@if (session('success'))<div class="notice">{{ session('success') }}</div>@endif
<div class="card">
@if ($orders->isEmpty())
<p>Belum ada pesanan tersimpan. Isi formulir checkout lalu klik <strong>Hitung &amp; Simpan</strong>.</p>
@else
<table>
<thead><tr><th>ID</th><th>Waktu</th><th>Harga</th><th>Qty</th><th>Member</th><th>Ekspres</th><th>Diskon</th><th class="num">Total</th></tr></thead>
<tbody>
@foreach($orders as $order)
<tr>
<td>#{{ $order->id }}</td><td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
<td>Rp{{ number_format($order->harga,0,',','.') }}</td><td>{{ $order->jumlah }}</td>
<td>{{ $order->member ? 'Ya' : 'Tidak' }}</td><td>{{ $order->ekspres ? 'Ya' : 'Tidak' }}</td>
<td>{{ $order->persentase_diskon }}%</td><td class="num"><strong>Rp{{ number_format($order->total,0,',','.') }}</strong></td>
</tr>
@endforeach
</tbody>
</table>
<div class="pagination">{{ $orders->links() }}</div>
@endif
</div>
<p><small>Latihan lokal: belum tersedia autentikasi/otorisasi. Jangan gunakan endpoint pesanan publik di lingkungan produksi.</small></p>
</main><footer>Laravel PHPUnit Lab · Database, Migration, Eloquent &amp; Feature Testing</footer>
</body></html>
