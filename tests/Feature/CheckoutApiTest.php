<?php

namespace Tests\Feature;

use Tests\TestCase;

class CheckoutApiTest extends TestCase
{
    public function test_halaman_checkout_dapat_dibuka(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Simulasi Checkout Laravel');
    }

    public function test_endpoint_api_checkout_sukses(): void
    {
        $this->postJson('/api/checkout', [
            'harga' => 100000,
            'jumlah' => 2,
            'member' => true,
            'ekspres' => true,
        ])
            ->assertOk()
            ->assertJsonPath('data.subtotal', 200000)
            ->assertJsonPath('data.persentase_diskon', 10)
            ->assertJsonPath('data.ongkir', 35000)
            ->assertJsonPath('data.total', 215000);
    }

    public function test_validasi_harga_tidak_valid_menghasilkan_422(): void
    {
        $this->postJson('/api/checkout', [
            'harga' => 0,
            'jumlah' => 1,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['harga']);
    }

    public function test_validasi_jumlah_kosong_menghasilkan_422(): void
    {
        $this->postJson('/api/checkout', [
            'harga' => 10000,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['jumlah']);
    }
}
