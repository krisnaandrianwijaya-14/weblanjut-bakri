<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('client.admin.dashboard');
})->name('dashboard');

Route::get('/test-scope/students/{student}', function (\App\Models\Client $client, \App\Models\Student $student) {
    return response()->json([
        'client_slug' => $client->slug,
        'student_name' => $student->name,
        'student_client' => $student->client_id,
        'match' => $student->client_id === $client->id,
    ]);
});
