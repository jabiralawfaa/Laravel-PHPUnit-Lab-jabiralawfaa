<?php

namespace Tests\Feature;

use App\Models\CheckoutOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutOrderWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_tombol_simpan_pesanan_menyimpan_database_dan_redirect(): void
    {
        $this->post('/orders', [
            'harga' => 100000,
            'jumlah' => 2,
            'member' => 1,
            'ekspres' => 1,
        ])->assertRedirect(route('orders.index'));

        $this->assertDatabaseHas('checkout_orders', [
            'subtotal' => 200000,
            'total' => 215000,
        ]);
    }

    public function test_halaman_riwayat_menampilkan_record_database(): void
    {
        $order = CheckoutOrder::factory()->create(['harga' => 100000, 'jumlah' => 1,
            'member' => false, 'ekspres' => false,
            'subtotal' => 100000, 'persentase_diskon' => 0, 'potongan_diskon' => 0,
            'subtotal_setelah_diskon' => 100000, 'ongkir' => 20000, 'total' => 120000]);

        $this->get('/orders')->assertOk()
            ->assertSee('Riwayat Pesanan')
            ->assertSee('Rp120.000');
        self::assertSame(120000, $order->fresh()->total);
    }

    public function test_invalid_web_request_tidak_menyimpan_pesanan(): void
    {
        $this->from('/')->post('/orders', ['harga' => -1, 'jumlah' => 1])
            ->assertRedirect('/')->assertSessionHasErrors('harga');
        $this->assertDatabaseCount('checkout_orders', 0);
    }
}
