<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{

    use BelongsToClient;

    protected $fillable = [
        'client_id', 'student_id', 'type', 'status',
        'device_id', 'location_type', 'location_id', 'scanned_at', 'direction',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(AttendanceDevice::class, 'device_id', 'device_id');
    }
}
