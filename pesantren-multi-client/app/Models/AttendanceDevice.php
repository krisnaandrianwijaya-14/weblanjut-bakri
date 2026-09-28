<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceDevice extends Model
{
    protected $fillable = [
        'client_id', 'device_id', 'name',
        'location_type', 'location_id', 'api_key', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = ['api_key'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
