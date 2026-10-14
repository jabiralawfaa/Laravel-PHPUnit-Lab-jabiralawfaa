<?php

namespace Tests\Unit;

use App\Services\DiskonService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DiskonServiceTest extends TestCase
{
    /**
     * P1, P2, P3 membentuk salah satu basis jalur independen (V(G)=3).
     * P4 menutup kombinasi eksekusi yang tersisa (4 dari 4 path).
     * @return array<string, array{bool, int, int}>
     */
    public static function dataJalur(): array
    {
        return [
            'P1_nonmember_rendah' => [false, 100000, 0],
            'P2_member_rendah' => [true, 100000, 10],
            'P3_nonmember_tinggi' => [false, 600000, 5],
            'P4_member_tinggi' => [true, 600000, 15],
        ];
    }

    #[DataProvider('dataJalur')]
    public function test_semua_kombinasi_jalur(bool $member, int $subtotal, int $harapan): void
    {
        $aktual = (new DiskonService())->persentase($member, $subtotal);
        self::assertSame($harapan, $aktual);
    }

    public function test_batas_499999_belum_memperoleh_diskon_nominal(): void
    {
        self::assertSame(0, (new DiskonService())->persentase(false, 499999));
    }

    public function test_batas_500000_sudah_memperoleh_diskon_nominal(): void
    {
        self::assertSame(5, (new DiskonService())->persentase(false, 500000));
    }
}
