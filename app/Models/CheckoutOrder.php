<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Snapshot hasil perhitungan checkout ketika transaksi disimpan.
 * Semua nilai rupiah adalah bilangan bulat, bukan float.
 */
class CheckoutOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'harga',
        'jumlah',
        'member',
        'ekspres',
        'subtotal',
        'persentase_diskon',
        'potongan_diskon',
        'subtotal_setelah_diskon',
        'ongkir',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'jumlah' => 'integer',
            'member' => 'boolean',
            'ekspres' => 'boolean',
            'subtotal' => 'integer',
            'persentase_diskon' => 'integer',
            'potongan_diskon' => 'integer',
            'subtotal_setelah_diskon' => 'integer',
            'ongkir' => 'integer',
            'total' => 'integer',
        ];
    }
}
