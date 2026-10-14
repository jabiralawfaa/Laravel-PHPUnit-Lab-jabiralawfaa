<?php

namespace Tests\Feature;

use App\Models\CheckoutOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutOrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_orders_menyimpan_hasil_checkout_yang_dihitung_server(): void
    {
        $this->postJson('/api/orders', [
            'harga' => 300000,
            'jumlah' => 2,
            'member' => true,
            'ekspres' => false,
            // Payload manipulasi diabaikan karena tidak diambil dari validated().
            'total' => 1,
            'persentase_diskon' => 100,
        ])
            ->assertCreated()
            ->assertJsonStructure(['data' => ['id', 'created_at']])
            ->assertJsonPath('data.subtotal', 600000)
            ->assertJsonPath('data.persentase_diskon', 15)
            ->assertJsonPath('data.potongan_diskon', 90000)
            ->assertJsonPath('data.ongkir', 0)
            ->assertJsonPath('data.total', 510000);

        $this->assertDatabaseCount('checkout_orders', 1);
        $this->assertDatabaseHas('checkout_orders', [
            'harga' => 300000,
            'jumlah' => 2,
            'subtotal' => 600000,
            'potongan_diskon' => 90000,
            'total' => 510000,
        ]);
    }

    public function test_api_checkout_lama_tidak_menyimpan_database(): void
    {
        $this->postJson('/api/checkout', ['harga' => 100000, 'jumlah' => 1])
            ->assertOk()
            ->assertJsonPath('data.total', 120000);

        $this->assertDatabaseCount('checkout_orders', 0);
    }

    public function test_input_tidak_valid_tidak_menghasilkan_record(): void
    {
        $this->postJson('/api/orders', ['harga' => 0, 'jumlah' => 2])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['harga']);

        $this->assertDatabaseCount('checkout_orders', 0);
    }

    public function test_api_dapat_mengambil_pesanan_yang_disimpan(): void
    {
        $order = CheckoutOrder::factory()->create();

        $this->getJson('/api/orders/'.$order->id)
            ->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.total', $order->total);
    }

    public function test_id_pesanan_tidak_ditemukan_menghasilkan_404(): void
    {
        $this->getJson('/api/orders/999999')->assertNotFound();
    }

    public function test_factory_bisa_membuat_data_valid(): void
    {
        CheckoutOrder::factory()->count(3)->create();

        $this->assertDatabaseCount('checkout_orders', 3);
        self::assertTrue(CheckoutOrder::query()->get()->every(
            fn (CheckoutOrder $order): bool => $order->total >= 0
        ));
    }

    public function test_seeder_membuat_dua_belas_pesanan(): void
    {
        $this->seed();
        $this->assertDatabaseCount('checkout_orders', 12);
    }
}
