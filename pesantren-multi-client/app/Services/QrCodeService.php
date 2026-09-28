<?php

namespace App\Services;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generateForStudent(string $token, string $studentNumber): string
    {
        $filename = 'qr-codes/' . $studentNumber . '-' . md5($token) . '.png';

        return $this->generateAndSave($token, $filename);
    }

    public function generate(string $token, string $filename): string
    {
        return $this->generateAndSave($token, 'qr-codes/' . $filename . '.png');
    }

    private function generateAndSave(string $data, string $relativePath): string
    {
        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        Storage::disk('public')->put($relativePath, $result->getString());

        return $relativePath;
    }
}
