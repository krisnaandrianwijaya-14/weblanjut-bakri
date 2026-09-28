<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermitViolation extends Model
{
    use BelongsToClient;

    protected $fillable = [
        'client_id', 'student_id', 'leave_request_id',
        'late_minutes', 'notes', 'recorded_at', 'recorded_by',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }
}
