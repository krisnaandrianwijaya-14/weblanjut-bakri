<x-layouts.asrama title="Dashboard Wali Asrama">
    @php
    $ctx = app(\App\Support\ClientContext::class);
    $clientName = $ctx->has() ? $ctx->client()->name : 'Pesantren';
@endphp
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-2">Assalamu'alaikum, {{ auth()->user()->name }}</h2>
        <p class="text-slate-600">Dashboard wali asrama akan dibangun pada minggu berikutnya.</p>
    </div>
</x-layouts.asrama>
