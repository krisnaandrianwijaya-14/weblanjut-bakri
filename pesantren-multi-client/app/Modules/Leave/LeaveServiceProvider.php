<?php

namespace App\Modules\Leave;

use Illuminate\Support\ServiceProvider;

final class LeaveServiceProvider extends ServiceProvider
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
