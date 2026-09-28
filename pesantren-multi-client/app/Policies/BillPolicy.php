<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;
use App\Support\ClientContext;

class BillPolicy
{
    public function view(User $user, Bill $bill): bool
    {
        // Admin/keuangan lihat semua
        if (in_array($user->role, ['admin_pesantren', 'keuangan'])) {
            return $bill->client_id === app(ClientContext::class)->idOrNull();
        }

        // Wali santri lihat tagihan anaknya
        if ($user->role === 'wali_santri') {
            return $bill->student->guardians
                ->contains(fn ($g) => $g->user_id === $user->id);
        }

        // Santri lihat tagihan sendiri
        if ($user->role === 'santri') {
            return $bill->student->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin_pesantren', 'keuangan'])
            && app(ClientContext::class)->has();
    }

    public function update(User $user, Bill $bill): bool
    {
        return $bill->client_id === app(ClientContext::class)->idOrNull()
            && in_array($user->role, ['admin_pesantren', 'keuangan']);
    }
}
