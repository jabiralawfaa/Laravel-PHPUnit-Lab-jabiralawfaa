<?php

namespace App\Services;

class OngkirService
{
    /**
     * Ongkir reguler Rp20.000, gratis jika net subtotal >= Rp300.000.
     * Ekspres menambah Rp15.000, termasuk ketika reguler gratis.
     */
    public function hitung(int $subtotalSetelahDiskon, bool $ekspres): int
    {
        $ongkir = 20000;

        if ($subtotalSetelahDiskon >= 300000) {
            $ongkir = 0;
        }

        if ($ekspres) {
            $ongkir += 15000;
        }

        return $ongkir;
    }
}
