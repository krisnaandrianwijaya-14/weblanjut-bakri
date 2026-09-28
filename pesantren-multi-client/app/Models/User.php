<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'email', 'password', 'role', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'status' => 'string',
        ];
    }
    public function hasRole(string $role): bool
{
    return $this->role === $role && $this->isActive();
}

public function isActive(): bool
{
    return $this->status === 'active';
}

public function clientUsers(): HasMany
{
    return $this->hasMany(ClientUser::class);
}

public function clients(): BelongsToMany
{
    return $this->belongsToMany(Client::class, 'client_users')
                ->withPivot(['role', 'is_active'])
                ->withTimestamps();
}

/**
 * Slug client pertama yang aktif untuk user ini.
 * Dipakai untuk redirect setelah login.
 */
public function defaultClientSlug(): string
{
    $slug = $this->clientUsers()
        ->where('is_active', true)
        ->whereHas('client', fn ($q) => $q->where('status', 'active'))
        ->with('client')
        ->first()
        ?->client
        ?->slug;

    return $slug ?? 'demo';
}
}
