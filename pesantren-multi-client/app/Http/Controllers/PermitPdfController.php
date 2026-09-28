<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class PermitPdfController extends Controller
{
    public function show(LeaveRequest $permit)
    {
        $permit->load('student.client');

        $pdf = Pdf::loadView('permits.surat-izin', [
            'permit' => $permit,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("surat-izin-{$permit->code}.pdf");
        // atau ->stream() untuk preview di browser
    }
}
