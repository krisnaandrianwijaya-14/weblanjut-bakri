<x-layouts.guest title="Masuk">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Masuk Sistem</h1>
        <p class="text-sm text-slate-500 mb-6">Gunakan akun yang telah terdaftar.</p>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                    Email
                </label>
                <input id="email"
                       name="email"
                       type="email"
                       required
                       autofocus
                       value="{{ old('email') }}"
                       placeholder="nama@pesantren.test"
                       class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                    Password
                </label>
                <input id="password"
                       name="password"
                       type="password"
                       required
                       placeholder="••••••••"
                       class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
            </div>

            <button type="submit"
                    class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-medium py-2.5 rounded-md transition">
                Masuk
            </button>
        </form>

        <p class="mt-6 text-xs text-slate-400 text-center">
            Sistem Manajemen Pesantren Multi-Client
        </p>
    </div>
</x-layouts.guest>
