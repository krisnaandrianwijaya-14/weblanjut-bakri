<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermitPdfController;
use App\Http\Controllers\Api\PermitController;

Route::get('/', function () {
    // Kalau sudah login → arahkan ke dashboard sesuai role
if (Auth::check()) {
    /** @var \App\Models\User $user */
    $user = Auth::user();

        return match ($user->role) {
            'platform_admin'  => redirect('/platform'),
            'admin_pesantren' => redirect('/client/'.$user->defaultClientSlug().'/admin/dashboard'),
            'guru'            => redirect('/client/'.$user->defaultClientSlug().'/guru/dashboard'),
            'wali_asrama'     => redirect('/client/'.$user->defaultClientSlug().'/asrama/dashboard'),
            'wali_santri'     => redirect('/client/'.$user->defaultClientSlug().'/wali/dashboard'),
            'santri'          => redirect('/client/'.$user->defaultClientSlug().'/santri/dashboard'),
            default           => redirect('/login'),
        };
    }

    // Kalau belum login → tampilkan form login langsung
    return view('auth.login');
})->name('root');

// Logout GET (workaround development)
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout.get');

// Serve QR image langsung dari storage (bypass symlink)
Route::get('/qr-image/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);

    if (! file_exists($fullPath)) {
        abort(404, 'QR image not found');
    }

    return response()->file($fullPath);
})->where('path', '.*')->name('qr.image');

Route::get('/students/{student}/qr', function (\App\Models\Student $student) {
    if (! $student->qr_image_path) {
        abort(404, 'QR belum dibuat untuk santri ini');
    }

    $fullPath = storage_path('app/public/' . $student->qr_image_path);

    if (! file_exists($fullPath)) {
        abort(404, 'File QR tidak ditemukan di storage');
    }

    return response()->file($fullPath);
})->name('students.qr');

Route::get('/permits/{permit}/pdf', [PermitPdfController::class, 'show'])
    ->name('permits.pdf');

    // routes/api.php
Route::middleware('auth:sanctum')->prefix('permits')->group(function () {
    Route::post('/initiate', [PermitController::class, 'initiate']);
    Route::post('/depart',   [PermitController::class, 'depart']);
    Route::post('/report',   [PermitController::class, 'report']);
});
