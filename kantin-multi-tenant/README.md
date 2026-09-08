Requirements (Prasyarat Sistem): Menjabarkan daftar minimal perangkat lunak yang wajib ada seperti PHP 8.3+, Laravel 13.x, Livewire 4.x, Reverb 1.x, MariaDB 10.6+, Redis 7.x, dan versi Node.js LTS
. Ini juga mencakup informasi port-port penting (8000, 8080, 3306, dan 6379) yang harus bebas dari konflik
.
Setup (Langkah Instalasi): Alur instalasi awal mulai dari kloning repositori
, instalasi dependensi via Composer & NPM
, konfigurasi berkas .env
, hingga inisialisasi basis data terpisah (untuk pengembangan dan testing otomatis agar data kerja Anda tidak rusak)
. Di bagian ini juga dijelaskan penggunaan pintasan composer setup untuk otomatisasi seluruh langkah di atas
.
Run (Menjalankan Aplikasi): Panduan menjalankan server lokal, Vite hot-reload, dan queue worker secara sekaligus menggunakan perintah terintegrasi composer run dev
, maupun perintah manual secara terpisah (termasuk untuk server WebSocket Reverb)
.
Test (Quality Gate): Standar pemeriksaan kode otomatis (Quality Gate) sebelum Anda melakukan commit ke Git
. Menjelaskan cara menjalankan composer test
 serta eksekusi manual untuk Laravel Pint
, Larastan/PHPStan
, dan PHPUnit (php artisan test)
.
Troubleshooting (Penyelesaian Masalah): Selain masalah umum seperti pdo_mysql driver yang belum aktif
, port bentrok
, dan masalah Vite manifest
, saya juga telah menambahkan panduan khusus mengenai penanganan konflik dependensi (guzzlehttp/psr7) yang sempat Anda alami saat menginstalasi Laravel Reverb. Ini akan menjadi panduan yang sangat berguna bagi rekan satu tim Anda jika mereka mengalami kendala serupa saat mencoba melakukan instalasi dari awal.
