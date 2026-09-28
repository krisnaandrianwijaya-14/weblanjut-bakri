<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use App\Support\ClientContext;

class AttendancePolicy
{
    public function view(User $user, Attendance $attendance): bool
    {
        return $attendance->client_id === app(ClientContext::class)->idOrNull();
    }

    public function create(User $user): bool
    {
        return app(ClientContext::class)->has()
            && in_array($user->role, ['admin_pesantren', 'keamanan', 'wali_asrama', 'guru']);
    }
}
