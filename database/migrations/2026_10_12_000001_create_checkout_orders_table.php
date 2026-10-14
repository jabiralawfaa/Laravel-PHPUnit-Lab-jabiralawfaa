<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkout_orders', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('harga');
            $table->unsignedInteger('jumlah');
            $table->boolean('member')->default(false);
            $table->boolean('ekspres')->default(false);
            $table->unsignedBigInteger('subtotal');
            $table->unsignedTinyInteger('persentase_diskon');
            $table->unsignedBigInteger('potongan_diskon');
            $table->unsignedBigInteger('subtotal_setelah_diskon');
            $table->unsignedBigInteger('ongkir');
            $table->unsignedBigInteger('total');
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_orders');
    }
};
