<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use App\Support\ClientContext;

class StudentPolicy
{
    /**
     * Semua user dalam client boleh lihat student client sendiri.
     */
    public function view(User $user, Student $student): bool
    {
        return $student->client_id === app(ClientContext::class)->idOrNull();
    }

    /**
     * Semua user boleh list student client sendiri.
     */
    public function viewAny(User $user): bool
    {
        return app(ClientContext::class)->has();
    }

    /**
     * Hanya admin pesantren & wali asrama boleh create.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin_pesantren', 'wali_asrama']);
    }

    /**
     * Hanya admin pesantren boleh update student.
     */
    public function update(User $user, Student $student): bool
    {
        return $student->client_id === app(ClientContext::class)->idOrNull()
            && $user->role === 'admin_pesantren';
    }

    /**
     * Hanya admin pesantren boleh delete student.
     */
    public function delete(User $user, Student $student): bool
    {
        return $student->client_id === app(ClientContext::class)->idOrNull()
            && $user->role === 'admin_pesantren';
    }
}
