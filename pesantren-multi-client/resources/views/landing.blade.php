<x-layouts.guest title="Selamat Datang">
    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
        <h1 class="text-3xl font-bold text-emerald-800 mb-3">
            Sistem Manajemen Pesantren Multi-Client
        </h1>
        <p class="text-slate-600 mb-6">
            Platform untuk mengelola santri, akademik, asrama, keuangan, dan perizinan pesantren.
        </p>
        <a href="{{ route('login') }}"
           class="inline-block bg-emerald-700 hover:bg-emerald-800 text-white font-medium px-6 py-3 rounded-md transition">
            Masuk Sistem
        </a>
    </div>
</x-layouts.guest>
