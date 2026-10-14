<?php

namespace App\Services;

use App\Models\CheckoutOrder;

/**
 * Menjembatani logika murni perhitungan dengan persistensi Eloquent.
 * Unit Test CheckoutService tetap bisa berjalan tanpa database.
 */
class CheckoutOrderService
{
    public function __construct(private readonly CheckoutService $checkoutService)
    {
    }

    public function simpan(int $harga, int $jumlah, bool $member, bool $ekspres): CheckoutOrder
    {
        // Jangan mempercayai total/diskon dari request: hitung ulang di server.
        $perhitungan = $this->checkoutService->hitung($harga, $jumlah, $member, $ekspres);

        return CheckoutOrder::query()->create($perhitungan);
    }
}
