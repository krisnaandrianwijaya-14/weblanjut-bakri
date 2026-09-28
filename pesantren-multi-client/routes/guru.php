<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('client.guru.dashboard');
})->name('dashboard');
