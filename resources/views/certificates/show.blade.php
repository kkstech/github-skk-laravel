<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $certificate->nama }}</title>
    <meta name="description" content="Sertifikat Kompetensi Kerja {{ $certificate->nama }}">
    
    <!-- html2pdf for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }

        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            min-height: 100vh;
        }

        .actions {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-back {
            background-color: #64748b;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 8px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }

        .btn-back:hover { background-color: #475569; }

        .btn-export {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
            transition: background 0.3s, transform 0.2s;
        }

        .btn-export:hover { background-color: #2563eb; transform: translateY(-2px); }

        .cert-container {
            background-color: white;
            width: 100%;
            max-width: 600px;
            border: 4px solid #4bb4e6;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .cert-header {
            background-color: #4bb4e6;
            color: white;
            text-align: center;
            padding: 12px;
            font-size: 16px;
            font-weight: normal;
        }

        .cert-body { padding: 15px; }

        .data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .data-label { color: #999; flex: 0 0 40%; }

        .data-value {
            color: #333;
            flex: 0 0 60%;
            text-align: right;
            text-transform: uppercase;
        }

        .data-value.normal-case { text-transform: none; }

        @media print {
            .actions { display: none !important; }
            body { background-color: white; }
            .cert-container { box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="actions" id="action-bar">
        <a href="{{ url('/') }}" class="btn-back">← Kembali</a>
        <button class="btn-export" onclick="exportPDF()">📥 Download / Export PDF</button>
    </div>

    <div class="cert-container" id="cert-view">
        <div class="cert-header">
            Sertifikat Kompetensi Kerja (SKK) Konstruksi
        </div>
        <div class="cert-body">
            <div class="data-row">
                <div class="data-label">Nama:</div>
                <div class="data-value">{{ $certificate->nama }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Provinsi:</div>
                <div class="data-value normal-case">{{ $certificate->provinsi }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Kabupaten:</div>
                <div class="data-value normal-case">{{ $certificate->kabupaten }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Klasifikasi:</div>
                <div class="data-value">{{ $certificate->klasifikasi }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Subklasifikasi:</div>
                <div class="data-value normal-case">{{ $certificate->subklasifikasi }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Kualifikasi:</div>
                <div class="data-value">{{ $certificate->kualifikasi }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Kode Jabatan Kerja:</div>
                <div class="data-value">{{ $certificate->kode_jabatan }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Jabatan Kerja:</div>
                <div class="data-value normal-case">{{ $certificate->jabatan_kerja }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Nomor Registrasi:</div>
                <div class="data-value">{{ $certificate->nomor_registrasi }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Nama LSP:</div>
                <div class="data-value">{{ $certificate->nama_lsp }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Nama Asosiasi:</div>
                <div class="data-value">{{ $certificate->nama_asosiasi }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Tanggal Ditetapkan:</div>
                <div class="data-value">{{ $certificate->tanggal_ditetapkan ? $certificate->tanggal_ditetapkan->translatedFormat('d F Y') : '' }}</div>
            </div>
            <div class="data-row">
                <div class="data-label">Tanggal Masa Berlaku<br>Sampai Dengan:</div>
                <div class="data-value" style="display: flex; align-items: flex-end; justify-content: flex-end;">
                    {{ $certificate->tanggal_berlaku ? $certificate->tanggal_berlaku->translatedFormat('d F Y') : '' }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportPDF() {
            const element = document.getElementById('cert-view');
            const safeName = '{{ addslashes(preg_replace("/[^a-zA-Z0-9_]/", "_", $certificate->nama)) }}';

            const opt = {
                margin:      0.5,
                filename:    `Sertifikat_${safeName}.pdf`,
                image:       { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF:       { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
