<?php

namespace App\Services;

class DiskonService
{
    /**
     * Kebijakan diskon:
     * - anggota mendapat 10% tambahan;
     * - subtotal minimal Rp500.000 mendapat 5% tambahan.
     * Kedua aturan independen sehingga maksimum 15%.
     *
     * Prekondisi: subtotal non-negatif (divalidasi oleh CheckoutService).
     */
    public function persentase(bool $member, int $subtotal): int
    {
        $diskon = 0;

        if ($member) {
            $diskon += 10;
        }

        if ($subtotal >= 500000) {
            $diskon += 5;
        }

        return $diskon;
    }
}
