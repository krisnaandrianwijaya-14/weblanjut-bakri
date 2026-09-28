<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDevice;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'qr_token'      => 'required|string',
            'device_id'     => 'required|string',
            'location_type' => 'required|in:academic,dormitory,gate',
            'location_id'   => 'nullable|integer',
            'scanned_at'    => 'nullable|date',
        ]);

        // 1. Verifikasi device
        $device = AttendanceDevice::where('device_id', $validated['device_id'])
            ->where('is_active', true)
            ->first();

        if (! $device) {
            return response()->json([
                'success' => false,
                'message' => 'Device tidak terdaftar atau tidak aktif',
            ], 401);
        }

        // 2. Cari santri berdasarkan QR token + client_id dari device
        $student = Student::where('client_id', $device->client_id)
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak dikenali',
            ], 404);
        }

        // 3. Cek duplikat scan < 5 menit
        $recent = Attendance::where('student_id', $student->id)
            ->where('scanned_at', '>=', now()->subMinutes(5))
            ->latest('scanned_at')
            ->first();

        if ($recent) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah absen 5 menit yang lalu',
                'data' => [
                    'last_scan_at' => $recent->scanned_at,
                ],
            ], 409);
        }

        // 4. Simpan absensi
// ✅ PALING AMAN
$scannedAt = filled($validated['scanned_at'] ?? null)
    ? Carbon::parse($validated['scanned_at'])
    : now();

        $attendance = Attendance::create([
            'client_id'     => $device->client_id,
            'student_id'    => $student->id,
            'type'          => $validated['location_type'],
            'status'        => $this->determineStatus($scannedAt, $validated['location_type']),
            'device_id'     => $device->device_id,
            'location_type' => $validated['location_type'],
            'location_id'   => $validated['location_id'],
            'scanned_at'    => $scannedAt,
        ]);

        Log::info('Attendance recorded', [
            'student' => $student->name,
            'device'  => $device->device_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'student_name'   => $student->name,
                'student_number' => $student->student_number,
                'status'         => $attendance->status,
                'attendance_id'  => $attendance->id,
                'recorded_at'    => $attendance->scanned_at,
            ],
            'message' => 'Absensi berhasil dicatat',
        ]);
    }

    public function today()
    {
        $attendances = Attendance::with('student')
            ->whereDate('scanned_at', today())
            ->latest('scanned_at')
            ->limit(100)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $attendances,
        ]);
    }

    public function studentHistory(Student $student)
    {
        $history = Attendance::where('student_id', $student->id)
            ->latest('scanned_at')
            ->paginate(30);

        return response()->json([
            'success' => true,
            'data'    => $history,
        ]);
    }

    private function determineStatus(Carbon $scannedAt, string $locationType): string
    {
        $hour = (int) $scannedAt->format('H');

        if ($locationType === 'dormitory' && $hour >= 6) {
            return 'late';
        }

        return 'present';
    }
}
