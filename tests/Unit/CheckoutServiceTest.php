<?php

namespace Tests\Unit;

use App\Services\CheckoutService;
use App\Services\DiskonService;
use App\Services\OngkirService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CheckoutServiceTest extends TestCase
{
    public function test_hasil_checkout_dengan_dependensi_asli(): void
    {
        $service = new CheckoutService(new DiskonService(), new OngkirService());
        $hasil = $service->hitung(100000, 2, true, true);

        self::assertSame(200000, $hasil['subtotal']);
        self::assertSame(10, $hasil['persentase_diskon']);
        self::assertSame(20000, $hasil['potongan_diskon']);
        self::assertSame(180000, $hasil['subtotal_setelah_diskon']);
        self::assertSame(35000, $hasil['ongkir']);
        self::assertSame(215000, $hasil['total']);
    }

    public function test_mock_mengisolasi_dependensi(): void
    {
        // Menilai koordinasi CheckoutService tanpa menguji ulang implementasi diskon/ongkir.
        $diskon = $this->createMock(DiskonService::class);
        $ongkir = $this->createMock(OngkirService::class);

        $diskon->expects($this->once())
            ->method('persentase')
            ->with(true, 600000)
            ->willReturn(10);

        $ongkir->expects($this->once())
            ->method('hitung')
            ->with(540000, false)
            ->willReturn(0);

        $hasil = (new CheckoutService($diskon, $ongkir))->hitung(300000, 2, true, false);
        self::assertSame(540000, $hasil['total']);
    }

    public function test_harga_nol_ditolak(): void
    {
        $service = new CheckoutService(new DiskonService(), new OngkirService());
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Harga harus lebih dari nol.');
        $service->hitung(0, 1, false, false);
    }

    public function test_jumlah_nol_ditolak(): void
    {
        $service = new CheckoutService(new DiskonService(), new OngkirService());
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Jumlah harus lebih dari nol.');
        $service->hitung(10000, 0, false, false);
    }
}
