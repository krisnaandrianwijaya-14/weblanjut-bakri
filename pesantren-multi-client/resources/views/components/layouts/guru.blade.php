@props(['title' => 'Guru'])
<!DOCTYPE html>
<html lang="id" class="h-full bg-blue-50/40">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans">
    <div class="min-h-full flex">
        <aside class="w-64 bg-blue-900 text-white flex-shrink-0 hidden md:flex md:flex-col">
            <div class="p-4 border-b border-blue-800">
                <div class="text-xs uppercase text-blue-300">Guru</div>
                <div class="font-bold">{{ request()->route('client')->name ?? 'Pesantren' }}</div>
            </div>
            <nav class="flex-1 p-4 space-y-1 text-sm">
                <a href="#" class="block px-3 py-2 rounded hover:bg-blue-800">Dashboard</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-blue-800">Jadwal Mengajar</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-blue-800">Kelas Saya</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-blue-800">Absensi</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-blue-800">Input Nilai</a>
            </nav>
        </aside>
        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b px-6 py-4 flex items-center justify-between">
                <h1 class="text-lg font-semibold">{{ $title }}</h1>
                <div class="text-sm text-slate-600">{{ auth()->user()->name ?? 'Guest' }}</div>
            </header>
            <main class="p-6 flex-1">{{ $slot }}</main>
        </div>
    </div>
    <form method="POST" action="{{ route('logout') }}" class="inline">
    @csrf
    <button type="submit" class="text-sm text-red-600 hover:underline">
        Logout
    </button>
</form>
</body>
</html>
