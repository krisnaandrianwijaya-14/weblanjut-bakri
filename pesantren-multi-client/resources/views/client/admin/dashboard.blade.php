<x-layouts.client title="Dashboard Admin Pesantren">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-2">
            Assalamu'alaikum, {{ auth()->user()->name }}
        </h2>
        @php
    $ctx = app(\App\Support\ClientContext::class);
    $clientName = $ctx->has() ? $ctx->client()->name : 'Pesantren';
@endphp
        <p class="text-slate-600 mb-4">
            Dashboard admin <strong>{{ request()->route('client')->name ?? 'Pesantren' }}</strong>.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-emerald-50 rounded-lg p-4">
                <div class="text-sm text-emerald-600 font-medium">Total Santri</div>
                <div class="text-3xl font-bold text-emerald-900">0</div>
            </div>
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="text-sm text-blue-600 font-medium">Kelas</div>
                <div class="text-3xl font-bold text-blue-900">0</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <div class="text-sm text-purple-600 font-medium">Kamar</div>
                <div class="text-3xl font-bold text-purple-900">0</div>
            </div>
            <div class="bg-amber-50 rounded-lg p-4">
                <div class="text-sm text-amber-600 font-medium">Tagihan Bulan Ini</div>
                <div class="text-3xl font-bold text-amber-900">0</div>
            </div>
        </div>

        <p class="mt-6 text-sm text-slate-500">
            Modul CRUD santri, akademik, asrama, dan keuangan akan dibangun pada minggu-minggu berikutnya.
        </p>
    </div>
</x-layouts.client>
