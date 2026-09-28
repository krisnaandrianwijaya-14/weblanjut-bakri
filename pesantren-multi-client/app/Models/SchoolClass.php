<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory, BelongsToClient;

    protected $table = 'classes';

    protected $fillable = [
        'client_id', 'name', 'academic_year', 'level', 'homeroom_teacher_id',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_student')
                    ->withPivot(['academic_year'])
                    ->withTimestamps();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }
}
