<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'status', 'nsp', 'address', 'phone', 'email',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // ============ RELASI EXISTING ============
    public function clientUsers(): HasMany
    {
        return $this->hasMany(ClientUser::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_users')
                    ->withPivot(['role', 'is_active'])
                    ->withTimestamps();
    }

    // ============ RELASI BARU (untuk scoped binding) ============
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function guardians(): HasMany
    {
        return $this->hasMany(Guardian::class);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // ============ HELPER ============
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
