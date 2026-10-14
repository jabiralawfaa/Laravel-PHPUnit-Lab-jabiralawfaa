<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_checkout_orders_membuat_tabel_dan_kolom_penting(): void
    {
        self::assertTrue(Schema::hasTable('checkout_orders'));
        self::assertTrue(Schema::hasColumns('checkout_orders', [
            'id', 'harga', 'jumlah', 'member', 'ekspres',
            'subtotal', 'persentase_diskon', 'potongan_diskon',
            'subtotal_setelah_diskon', 'ongkir', 'total',
            'created_at', 'updated_at',
        ]));
    }
}
