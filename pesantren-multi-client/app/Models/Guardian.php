<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guardian extends Model
{
    use HasFactory, BelongsToClient;

    protected $fillable = [
        'client_id', 'name', 'relation', 'phone', 'email', 'address', 'occupation',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_guardians')
                    ->withPivot(['is_primary'])
                    ->withTimestamps();
    }
}
