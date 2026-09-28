@props(['title' => 'Platform'])
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — Platform Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans">
    <div class="min-h-full flex">
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:flex md:flex-col">
            <div class="p-4 text-lg font-bold border-b border-slate-700">
                🏛️ Platform Admin
            </div>
            <nav class="flex-1 p-4 space-y-1 text-sm">
                <a href="{{ route('platform.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Dashboard</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-slate-800">Daftar Client</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-slate-800">Modul</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-slate-800">Audit Log</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-slate-800">Pengguna Platform</a>
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
