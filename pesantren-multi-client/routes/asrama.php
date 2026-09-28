<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('client.asrama.dashboard');
})->name('dashboard');
