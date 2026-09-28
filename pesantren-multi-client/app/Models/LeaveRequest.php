<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
protected $fillable = [
    'client_id', 'student_id', 'code', 'type', 'reason',
    'start_time', 'end_time', 'deadline', 'destination',
    'contact_name', 'contact_phone', 'status',
    'departed_at', 'returned_at', 'is_late', 'late_minutes',
    'issued_by', 'activated_by', 'completed_by',
];

protected $casts = [
    'start_time'   => 'datetime',
    'end_time'     => 'datetime',
    'deadline'     => 'datetime',
    'departed_at'  => 'datetime',
    'returned_at'  => 'datetime',
    'is_late'      => 'boolean',
];

public function student()       { return $this->belongsTo(Student::class); }
public function violations()    { return $this->hasMany(PermitViolation::class); }
public function issuedBy()      { return $this->belongsTo(User::class, 'issued_by'); }
public function activatedBy()   { return $this->belongsTo(User::class, 'activated_by'); }
public function completedBy()   { return $this->belongsTo(User::class, 'completed_by'); }

// Helper
public function isWaitingSignature(): bool { return $this->status === 'menunggu_ttd_offline'; }
public function isActive(): bool           { return $this->status === 'izin_aktif'; }
public function isCompleted(): bool        { return $this->status === 'kembali_selesai'; }
}
