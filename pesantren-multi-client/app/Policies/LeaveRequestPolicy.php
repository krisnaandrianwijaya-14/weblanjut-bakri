<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Support\ClientContext;

class LeaveRequestPolicy
{
    public function view(User $user, LeaveRequest $permit): bool
    {
        // Admin/keamanan lihat semua di client-nya
        if (in_array($user->role, ['admin_pesantren', 'keamanan', 'wali_asrama'])) {
            return $permit->client_id === app(ClientContext::class)->idOrNull();
        }

        // Wali santri lihat izin anaknya
        if ($user->role === 'wali_santri') {
            return $permit->student->guardians
                ->contains(fn ($g) => $g->user_id === $user->id);
        }

        // Santri lihat izin sendiri
        if ($user->role === 'santri') {
            return $permit->student->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin_pesantren', 'keamanan']);
    }

    public function activate(User $user, LeaveRequest $permit): bool
    {
        // Hanya keamanan yang boleh aktifkan keluar
        return $permit->client_id === app(ClientContext::class)->idOrNull()
            && $user->role === 'keamanan'
            && $permit->status === 'menunggu_ttd_offline';
    }

    public function report(User $user, LeaveRequest $permit): bool
    {
        return $permit->client_id === app(ClientContext::class)->idOrNull()
            && $user->role === 'keamanan'
            && $permit->status === 'izin_aktif';
    }
}
