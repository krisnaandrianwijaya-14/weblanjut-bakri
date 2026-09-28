<?php

namespace App\Modules\Reporting;

use Illuminate\Support\ServiceProvider;

final class ReportingServiceProvider extends ServiceProvider
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
