<x-layouts.platform title="Dashboard Platform">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-2">
            Assalamu'alaikum, {{ auth()->user()->name }}
        </h2>
        <p class="text-slate-600 mb-6">
            Selamat datang di dashboard Platform Admin. Anda bisa mengelola client, modul, dan audit log di sini.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="text-sm text-blue-600 font-medium">Total Client</div>
                <div class="text-3xl font-bold text-blue-900">{{ \App\Models\Client::count() }}</div>
            </div>
            <div class="bg-emerald-50 rounded-lg p-4">
                <div class="text-sm text-emerald-600 font-medium">Client Aktif</div>
                <div class="text-3xl font-bold text-emerald-900">
                    {{ \App\Models\Client::where('status', 'active')->count() }}
                </div>
            </div>
            <div class="bg-amber-50 rounded-lg p-4">
                <div class="text-sm text-amber-600 font-medium">Total User</div>
                <div class="text-3xl font-bold text-amber-900">{{ \App\Models\User::count() }}</div>
            </div>
        </div>
    </div>
</x-layouts.platform>
