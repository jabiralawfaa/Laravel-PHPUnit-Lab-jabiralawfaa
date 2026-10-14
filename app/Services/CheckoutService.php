<?php

namespace App\Services;

use InvalidArgumentException;

class CheckoutService
{
    public function __construct(
        private readonly DiskonService $diskonService,
        private readonly OngkirService $ongkirService,
    ) {
    }

    /**
     * @return array<string, int|bool>
     */
    public function hitung(int $harga, int $jumlah, bool $member, bool $ekspres): array
    {
        if ($harga <= 0) {
            throw new InvalidArgumentException('Harga harus lebih dari nol.');
        }

        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah harus lebih dari nol.');
        }

        $subtotal = $harga * $jumlah;
        $persentaseDiskon = $this->diskonService->persentase($member, $subtotal);
        $potonganDiskon = intdiv($subtotal * $persentaseDiskon, 100);
        $subtotalSetelahDiskon = $subtotal - $potonganDiskon;
        $ongkir = $this->ongkirService->hitung($subtotalSetelahDiskon, $ekspres);

        return [
            'harga' => $harga,
            'jumlah' => $jumlah,
            'member' => $member,
            'ekspres' => $ekspres,
            'subtotal' => $subtotal,
            'persentase_diskon' => $persentaseDiskon,
            'potongan_diskon' => $potonganDiskon,
            'subtotal_setelah_diskon' => $subtotalSetelahDiskon,
            'ongkir' => $ongkir,
            'total' => $subtotalSetelahDiskon + $ongkir,
        ];
    }
}
