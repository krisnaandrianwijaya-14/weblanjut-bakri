<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDevice;
use App\Models\LeaveRequest;
use App\Models\PermitViolation;
use App\Models\Student;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermitController extends Controller
{
    use AuthorizesRequests;

    /**
     * UC-01: Inisiasi izin (scan pertama)
     */
    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'qr_token'      => 'required|string',
            'device_id'     => 'required|string',
            'type'          => 'required|in:pulang,keluar,sakit,lainnya',
            'reason'        => 'required|string|max:500',
            'deadline'      => 'required|date|after:now',
            'destination'   => 'nullable|string|max:200',
            'contact_name'  => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $device = $this->resolveDevice($validated['device_id']);
        if (! $device) {
            return $this->error('Device tidak terdaftar', 401);
        }

        $student = Student::where('client_id', $device->client_id)
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if (! $student) {
            return $this->error('QR Code tidak dikenali', 404);
        }

        // ⭐ AUTHORIZE — setelah student valid, sebelum aksi
        $this->authorize('create', LeaveRequest::class);

        // Cek: masih ada izin aktif?
        $activePermit = LeaveRequest::where('student_id', $student->id)
            ->whereIn('status', ['menunggu_ttd_offline', 'izin_aktif'])
            ->first();

        if ($activePermit) {
            return $this->error('Santri masih memiliki izin aktif', 409);
        }

        $permit = LeaveRequest::create([
            'client_id'     => $student->client_id,
            'student_id'    => $student->id,
            'code'          => 'IZN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'type'          => $validated['type'],
            'reason'        => $validated['reason'],
            'start_time'    => now(),
            'end_time'      => $validated['deadline'],
            'deadline'      => $validated['deadline'],
            'destination'   => $validated['destination'] ?? null,
            'contact_name'  => $validated['contact_name'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'status'        => 'menunggu_ttd_offline',
            'issued_by'     => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'permit_id'    => $permit->id,
                'permit_code'  => $permit->code,
                'student_name' => $student->name,
                'status'       => $permit->status,
                'pdf_url'      => route('permits.pdf', $permit->id),
            ],
            'message' => 'Surat izin dibuat. Silakan cetak dan minta TTD.',
        ], 201);
    }

    /**
     * UC-02: Aktivasi keluar (scan kedua)
     */
    public function depart(Request $request)
    {
        $validated = $request->validate([
            'qr_token'  => 'required|string',
            'device_id' => 'required|string',
        ]);

        $device = $this->resolveDevice($validated['device_id']);
        if (! $device) {
            return $this->error('Device tidak terdaftar', 401);
        }

        $student = Student::where('client_id', $device->client_id)
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if (! $student) {
            return $this->error('QR Code tidak dikenali', 404);
        }

        return DB::transaction(function () use ($student, $device) {
            // Pessimistic lock
            $permit = LeaveRequest::where('student_id', $student->id)
                ->where('status', 'menunggu_ttd_offline')
                ->lockForUpdate()
                ->first();

            if (! $permit) {
                return $this->error('Tidak ada izin menunggu aktivasi', 404);
            }

            // ⭐ AUTHORIZE — setelah permit valid
            $this->authorize('activate', $permit);

            $permit->update([
                'status'       => 'izin_aktif',
                'departed_at'  => now(),
                'activated_by' => Auth::id(),
            ]);

            $student->update([
                'presence_status'   => 'outside',
                'last_check_out_at' => now(),
            ]);

            Attendance::create([
                'client_id'     => $student->client_id,
                'student_id'    => $student->id,
                'type'          => 'gate',
                'status'        => 'present',
                'direction'     => 'out',
                'device_id'     => $device->device_id,
                'location_type' => 'gate',
                'scanned_at'    => now(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'student_name' => $student->name,
                    'permit_code'  => $permit->code,
                    'action'       => 'KELUAR',
                    'departed_at'  => $permit->departed_at,
                    'deadline'     => $permit->deadline,
                ],
                'message' => 'Santri resmi keluar. Notifikasi WA terkirim.',
            ]);
        });
    }

    /**
     * UC-03: Pelaporan kembali (scan ketiga)
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'qr_token'  => 'required|string',
            'device_id' => 'required|string',
        ]);

        $device = $this->resolveDevice($validated['device_id']);
        if (! $device) {
            return $this->error('Device tidak terdaftar', 401);
        }

        $student = Student::where('client_id', $device->client_id)
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if (! $student) {
            return $this->error('QR Code tidak dikenali', 404);
        }

        return DB::transaction(function () use ($student, $device) {
            $permit = LeaveRequest::where('student_id', $student->id)
                ->where('status', 'izin_aktif')
                ->lockForUpdate()
                ->first();

            if (! $permit) {
                return $this->error('Tidak ada izin aktif untuk dilaporkan', 404);
            }

            // ⭐ AUTHORIZE — setelah permit valid
            $this->authorize('report', $permit);

            $now = now();
            $isLate = $now->greaterThan($permit->deadline);
            $lateMinutes = $isLate
                ? (int) round($permit->deadline->diffInMinutes($now))
                : 0;

            $permit->update([
                'status'       => 'kembali_selesai',
                'returned_at'  => $now,
                'is_late'      => $isLate,
                'late_minutes' => $lateMinutes,
                'completed_by' => Auth::id(),
            ]);

            $student->update([
                'presence_status'  => 'inside',
                'last_check_in_at' => now(),
            ]);

            // Catat pelanggaran jika telat (append-only)
            if ($isLate) {
                PermitViolation::create([
                    'client_id'        => $student->client_id,
                    'student_id'       => $student->id,
                    'leave_request_id' => $permit->id,
                    'late_minutes'     => $lateMinutes,
                    'notes'            => "Terlambat {$lateMinutes} menit dari batas waktu "
                                        . $permit->deadline->format('d M Y H:i'),
                    'recorded_at'      => $now,
                    'recorded_by'      => Auth::id(),
                ]);
            }

            Attendance::create([
                'client_id'     => $student->client_id,
                'student_id'    => $student->id,
                'type'          => 'gate',
                'status'        => 'present',
                'direction'     => 'in',
                'device_id'     => $device->device_id,
                'location_type' => 'gate',
                'scanned_at'    => $now,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'student_name' => $student->name,
                    'permit_code'  => $permit->code,
                    'action'       => 'MASUK',
                    'returned_at'  => $now,
                    'is_late'      => $isLate,
                    'late_minutes' => $lateMinutes,
                ],
                'message' => $isLate
                    ? "Santri kembali terlambat {$lateMinutes} menit. Pelanggaran tercatat."
                    : 'Santri kembali tepat waktu.',
            ]);
        });
    }

    private function resolveDevice(string $deviceId): ?AttendanceDevice
    {
        return AttendanceDevice::where('device_id', $deviceId)
            ->where('is_active', true)
            ->first();
    }

    private function error(string $message, int $code)
    {
        return response()->json(['success' => false, 'message' => $message], $code);
    }
}
