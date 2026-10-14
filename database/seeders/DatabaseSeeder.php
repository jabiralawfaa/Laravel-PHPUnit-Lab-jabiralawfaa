<?php

namespace Database\Seeders;

use App\Models\CheckoutOrder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Data contoh untuk praktikum, tidak cocok untuk produksi.
        CheckoutOrder::factory()->count(12)->create();
    }
}
