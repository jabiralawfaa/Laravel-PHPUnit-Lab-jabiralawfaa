<?php

namespace Tests\Unit;

use App\Services\OngkirService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OngkirServiceTest extends TestCase
{
    /** @return array<string, array{int, bool, int}> */
    public static function kombinasiOngkir(): array
    {
        return [
            'reguler_berbayar' => [299999, false, 20000],
            'reguler_gratis' => [300000, false, 0],
            'ekspres_berbayar' => [299999, true, 35000],
            'ekspres_gratis_reguler' => [300000, true, 15000],
        ];
    }

    #[DataProvider('kombinasiOngkir')]
    public function test_ongkir(int $netto, bool $ekspres, int $harapan): void
    {
        self::assertSame($harapan, (new OngkirService())->hitung($netto, $ekspres));
    }
}
