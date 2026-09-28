<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use App\Services\QrCodeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Trait untuk model yang data-nya milik satu client.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Student extends Model
{
    use BelongsToClient;
    protected $fillable = [
        'client_id', 'name', 'student_number',
        'qr_token', 'qr_image_path', 'photo_path', 'status', 'presence_status', 'last_check_in_at', 'last_check_out_at',
    ];

        protected $casts = [
        'last_check_in_at'  => 'datetime',
        'last_check_out_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Student $student) {
            // Auto-generate QR token kalau belum ada
            if (empty($student->qr_token)) {
                $student->qr_token = self::generateQrToken($student);
            }
        });

        static::created(function (Student $student) {
            // Auto-generate QR image setelah record tersimpan
            if (empty($student->qr_image_path)) {
                $service = app(QrCodeService::class);
                $path = $service->generateForStudent(
                    $student->qr_token,
                    $student->student_number
                );

                $student->updateQuietly(['qr_image_path' => $path]);
            }
        });
    }

    /**
     * Generate token unik & stabil untuk santri.
     * Format: SANTRI-{client_id}-{tahun}-{random}
     */
    public static function generateQrToken(Student $student): string
    {
        $random = strtoupper(Str::random(8));

        return sprintf(
            'SANTRI-%04d-%s-%s',
            $student->client_id,
            now()->format('Y'),
            $random
        );
    }

    // ============ RELASI ============

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // ============ HELPER ============

    public function qrImageUrl(): ?string
    {
        if (! $this->qr_image_path) {
            return null;
        }

        return asset('storage/' . $this->qr_image_path);
    }

    public function photoUrl(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        return asset('storage/' . $this->photo_path);
    }

public function isInside(): bool
{
    return $this->presence_status === 'inside';
}
}

