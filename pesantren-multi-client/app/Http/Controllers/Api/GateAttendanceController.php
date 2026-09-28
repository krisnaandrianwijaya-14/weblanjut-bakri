<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDevice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GateAttendanceController extends Controller
{
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'qr_token'  => 'required|string',
            'device_id' => 'required|string',
        ]);

        // 1. Cek device
        $device = AttendanceDevice::where('device_id', $validated['device_id'])
            ->where('is_active', true)
            ->first();

        if (! $device) {
            return response()->json([
                'success' => false,
                'message' => 'Device tidak terdaftar',
            ], 401);
        }

        // 2. Cari santri (isolasi client otomatis)
        $student = Student::where('client_id', $device->client_id)
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak dikenali',
            ], 404);
        }

        // 3. Cek duplikat < 10 detik
        $lastScan = Attendance::where('student_id', $student->id)
            ->where('type', 'gate')
            ->latest('scanned_at')
            ->first();

        if ($lastScan && $lastScan->scanned_at->diffInSeconds(now()) < 10) {
            return response()->json([
                'success' => false,
                'message' => 'Scan terlalu cepat. Tunggu 10 detik.',
            ], 429);
        }

        // 4. Toggle status
        $isInside = $student->presence_status === 'inside';
        $direction = $isInside ? 'out' : 'in';
        $newStatus = $isInside ? 'outside' : 'inside';

        DB::transaction(function () use ($student, $direction, $newStatus, $device) {
            $student->update([
                'presence_status' => $newStatus,
                'last_check_in_at' => $direction === 'in' ? now() : $student->last_check_in_at,
                'last_check_out_at' => $direction === 'out' ? now() : $student->last_check_out_at,
            ]);

            Attendance::create([
                'client_id'     => $student->client_id,
                'student_id'    => $student->id,
                'type'          => 'gate',
                'status'        => 'present',
                'direction'     => $direction,
                'device_id'     => $device->device_id,
                'location_type' => 'gate',
                'scanned_at'    => now(),
            ]);
        });

        Log::info("Gate scan: {$student->name} {$direction}");

        return response()->json([
            'success' => true,
            'data' => [
                'student_name'    => $student->name,
                'student_number'  => $student->student_number,
                'direction'       => $direction,
                'action'          => $direction === 'in' ? 'MASUK' : 'KELUAR',
                'presence_status' => $newStatus,
                'scanned_at'      => now()->toIso8601String(),
            ],
            'message' => $direction === 'in'
                ? 'Santri masuk pesantren'
                : 'Santri keluar pesantren',
        ]);
    }

    public function currentlyOutside()
    {
        $students = Student::where('presence_status', 'outside')
            ->where('status', 'active')
            ->orderBy('last_check_out_at', 'desc')
            ->get(['id', 'name', 'student_number', 'last_check_out_at']);

        return response()->json([
            'success' => true,
            'total'   => $students->count(),
            'data'    => $students,
        ]);
    }

    public function history(Student $student)
    {
        $history = Attendance::where('student_id', $student->id)
            ->where('type', 'gate')
            ->orderBy('scanned_at', 'desc')
            ->paginate(30);

        return response()->json([
            'success' => true,
            'student' => $student->only(['id', 'name', 'student_number']),
            'data'    => $history,
        ]);
    }
}
