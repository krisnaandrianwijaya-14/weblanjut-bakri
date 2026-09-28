<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Support\ClientContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetClientContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $client = $request->route('client');

        // Kalau parameter bukan object Client (mis. slug), coba cari manual
        if (! $client instanceof Client) {
            $client = Client::query()
                ->where('slug', (string) $client)
                ->first();
        }

        if (! $client instanceof Client) {
            abort(404);
        }

        if (! $client->isActive()) {
            abort(403, 'Client tidak aktif.');
        }

        $context = app(ClientContext::class);
        $context->set($client);

        try {
            return $next($request);
        } finally {
            $context->clear();
        }
    }
}
