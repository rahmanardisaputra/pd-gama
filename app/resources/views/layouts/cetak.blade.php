<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cetak Dokumen')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 11pt;
            color: #000;
            background: #e2e8f0;
            margin: 0;
            padding: 20px 0;
        }

        .print-container {
            background: #fff;
            width: 210mm; /* A4 standard width */
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            box-sizing: border-box;
            position: relative;
        }

        .print-landscape {
            width: 297mm !important; /* A4 Landscape */
            min-height: 210mm !important;
            padding: 10mm 15mm !important;
        }

        .no-print-bar {
            position: fixed;
            top: 15px;
            right: 20px;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.9);
            padding: 10px 18px;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            display: flex;
            gap: 10px;
            align-items: center;
        }

        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .no-print, .no-print-bar {
                display: none !important;
            }
            .print-container {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            @page {
                size: auto;
                margin: 10mm;
            }
            .page-break {
                page-break-after: always;
                break-after: page;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Floating Print Action Bar -->
    <div class="no-print-bar">
        <button onclick="window.print()" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
            <i class="bi bi-printer-fill me-1"></i> Cetak / Simpan PDF
        </button>
        <button onclick="window.close(); if(history.length > 1) history.back();" class="btn btn-sm btn-outline-light rounded-pill px-3">
            <i class="bi bi-x-circle me-1"></i> Tutup
        </button>
    </div>

    @yield('content')

</body>
</html>

