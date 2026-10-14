<?php

namespace Database\Factories;

use App\Models\CheckoutOrder;
use App\Services\CheckoutService;
use App\Services\DiskonService;
use App\Services\OngkirService;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CheckoutOrder> */
class CheckoutOrderFactory extends Factory
{
    protected $model = CheckoutOrder::class;

    public function definition(): array
    {
        $harga = $this->faker->numberBetween(10000, 350000);
        $jumlah = $this->faker->numberBetween(1, 5);
        $member = $this->faker->boolean();
        $ekspres = $this->faker->boolean();

        return (new CheckoutService(new DiskonService(), new OngkirService()))
            ->hitung($harga, $jumlah, $member, $ekspres);
    }
}
