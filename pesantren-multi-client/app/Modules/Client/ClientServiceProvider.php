<?php
//app/Modules/Client/ClientServiceProvider.php

namespace App\Modules\Client;

use Illuminate\Support\ServiceProvider;

final class ClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding kontrak -> implementasi (diisi di modul lanjut)
    }

    public function boot(): void
    {
        // Rute, event listener, dan policy modul didaftarkan di sini.
    }
}
