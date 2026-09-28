<?php

namespace App\Modules\Announcement;

use Illuminate\Support\ServiceProvider;

final class AnnouncementServiceProvider extends ServiceProvider
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
