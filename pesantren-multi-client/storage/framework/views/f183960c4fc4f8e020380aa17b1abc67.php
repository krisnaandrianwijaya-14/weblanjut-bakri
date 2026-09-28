<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => 'Admin Pesantren']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title' => 'Admin Pesantren']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-emerald-50/40">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="h-full font-sans">
    <div class="min-h-full flex">
        <aside class="w-64 bg-emerald-900 text-white flex-shrink-0 hidden md:flex md:flex-col">
<?php
    $ctx = app(\App\Support\ClientContext::class);
    $clientName = $ctx->has() ? $ctx->client()->name : 'Pesantren';
?>
            <div class="text-xs uppercase text-emerald-300">Client</div>
<div class="font-bold">
    <?php echo e(app(\App\Support\ClientContext::class)->has() ? app(\App\Support\ClientContext::class)->client()->name : 'Pesantren'); ?>

</div>
            <nav class="flex-1 p-4 space-y-1 text-sm">
                <a href="#" class="block px-3 py-2 rounded hover:bg-emerald-800">Dashboard</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-emerald-800">Data Santri</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-emerald-800">Akademik</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-emerald-800">Asrama</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-emerald-800">Keuangan</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-emerald-800">Perizinan</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-emerald-800">Pengumuman</a>
                <a href="#" class="block px-3 py-2 rounded hover:bg-emerald-800">Laporan</a>
            </nav>
        </aside>
        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b px-6 py-4 flex items-center justify-between">
                <h1 class="text-lg font-semibold"><?php echo e($title); ?></h1>
                <div class="text-sm text-slate-600"><?php echo e(auth()->user()->name ?? 'Guest'); ?></div>
            </header>
            <main class="p-6 flex-1"><?php echo e($slot); ?></main>
        </div>
    </div>
    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
    <?php echo csrf_field(); ?>
    <button type="submit" class="text-sm text-red-600 hover:underline">
        Logout
    </button>
</form>
</body>
</html>
<?php /**PATH D:\ServBay\www\pesantren-multi-client\resources\views/components/layouts/client.blade.php ENDPATH**/ ?>