<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Service konkret dapat di-resolve otomatis oleh service container Laravel.
    }

    public function boot(): void
    {
    }
}
