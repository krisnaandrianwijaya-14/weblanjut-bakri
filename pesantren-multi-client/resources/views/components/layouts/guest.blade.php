@props(['title' => 'Selamat Datang'])
<!DOCTYPE html>
<html lang="id" class="h-full bg-emerald-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — Pesantren Multi-Client</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md">
        {{ $slot }}
    </div>
</body>
</html>
