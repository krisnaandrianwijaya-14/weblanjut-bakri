<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait untuk model yang data-nya milik satu client.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */

class ClientUser extends Model
{
    use BelongsToClient;

    protected $fillable = [
        'client_id', 'user_id', 'role', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
