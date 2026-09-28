<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin — {{ $permit->code }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.6; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16pt; }
        .header h2 { margin: 5px 0; font-size: 14pt; }
        .header p { margin: 0; font-size: 10pt; }
        .title { text-align: center; font-weight: bold; font-size: 14pt; text-decoration: underline; margin: 20px 0; }
        .content { margin: 20px 0; }
        .content table { width: 100%; }
        .content td { padding: 3px 0; vertical-align: top; }
        .content td:first-child { width: 30%; }
        .content td:nth-child(2) { width: 3%; }
        .signature { margin-top: 50px; }
        .signature table { width: 100%; text-align: center; }
        .signature td { vertical-align: top; padding: 5px; }
        .signature .name { margin-top: 70px; border-top: 1px solid #000; padding-top: 5px; display: inline-block; min-width: 150px; }
        .footer { margin-top: 40px; font-size: 10pt; font-style: italic; text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <h1>PONDOK PESANTREN {{ strtoupper($permit->student->client->name ?? '') }}</h1>
    <p>{{ $permit->student->client->address ?? '' }}</p>
</div>

<div class="title">SURAT KETERANGAN IZIN</div>
<p style="text-align: center; margin: -10px 0 20px;">Nomor: {{ $permit->code }}</p>

<div class="content">
    <p>Yang bertanda tangan di bawah ini menerangkan bahwa santri:</p>
    <table>
        <tr><td>Nama Santri</td><td>:</td><td><strong>{{ $permit->student->name }}</strong></td></tr>
        <tr><td>NIS</td><td>:</td><td>{{ $permit->student->student_number }}</td></tr>
        <tr><td>Jenis Izin</td><td>:</td><td>{{ ucfirst($permit->type) }}</td></tr>
        <tr><td>Alasan</td><td>:</td><td>{{ $permit->reason }}</td></tr>
        <tr><td>Tujuan</td><td>:</td><td>{{ $permit->destination ?? '-' }}</td></tr>
        <tr><td>Kontak Wali</td><td>:</td><td>{{ $permit->contact_name ?? '-' }} / {{ $permit->contact_phone ?? '-' }}</td></tr>
        <tr><td>Tenggat Kembali</td><td>:</td><td><strong>{{ $permit->deadline->format('d F Y, H:i') }} WIB</strong></td></tr>
    </table>

    <p style="margin-top: 20px;">Demikian surat izin ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
</div>

<div class="signature">
    <p style="text-align: right; margin-bottom: 20px;">
        {{ $permit->student->client->address ?? '' }}, {{ now()->format('d F Y') }}
    </p>

    <table>
        <tr>
            <td>
                <div>Petugas Keamanan</div>
                <div class="name"></div>
                <div style="margin-top: 5px;">(............................)</div>
            </td>
            <td>
                <div>Ketua Kamar</div>
                <div class="name"></div>
                <div style="margin-top: 5px;">(............................)</div>
            </td>
            <td>
                <div>Kepala Asrama</div>
                <div class="name"></div>
                <div style="margin-top: 5px;">(............................)</div>
            </td>
        </tr>
    </table>
</div>

<div class="footer">
    Surat ini wajib dibawa santri saat keluar-masuk gerbang pesantren.
</div>

</body>
</html>
