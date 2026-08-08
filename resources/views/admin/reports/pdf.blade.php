<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 30px 35px;
        }
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #334155;
            margin: 0;
        }

        /* Kop Surat */
        .header {
            border-bottom: 3px solid #1e293b;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .header .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }
        .header .company-address {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .header .report-title {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }
        .header .report-period {
            text-align: right;
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Kotak Ringkasan */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin-bottom: 20px;
        }
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            width: 33%;
        }
        .summary-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 3px;
        }
        .summary-value.highlight {
            color: #16a34a;
        }

        /* Tabel Detail */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .detail-table thead th {
            background: #1e293b;
            color: #ffffff;
            font-size: 10px;
            text-align: left;
            padding: 8px 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .detail-table tbody td {
            padding: 7px 6px;
            font-size: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .detail-table .col-code {
            font-family: monospace;
            color: #475569;
        }
        .detail-table .col-total {
            text-align: right;
            font-weight: bold;
            color: #1e293b;
        }

        /* Badge Status */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-selesai { background: #dcfce7; color: #166534; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }
        .badge-proses  { background: #fef9c3; color: #854d0e; }

        /* Footer */
        .footer-note {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- Kop Surat --}}
    <div class="header">
        <table>
            <tr>
                <td style="width: 60%;">
                    <div class="company-name">Jack Service Elektronik</div>
                    <div class="company-address">Bukittinggi, Sumatera Barat</div>
                </td>
                <td style="width: 40%;">
                    <div class="report-title">LAPORAN SERVIS &amp; PENDAPATAN</div>
                    <div class="report-period">
                        Periode: {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }}
                        s.d. {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Ringkasan --}}
    <table class="summary-table">
        <tr>
            <td class="summary-box">
                <div class="summary-label">Total Servis</div>
                <div class="summary-value">{{ $services->count() }}</div>
            </td>
            <td class="summary-box">
                <div class="summary-label">Servis Selesai</div>
                <div class="summary-value">{{ $services->where('status', 'Selesai')->count() }}</div>
            </td>
            <td class="summary-box">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value highlight">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Detail --}}
    <table class="detail-table">
        <thead>
            <tr>
                <th>Kode Servis</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Status</th>
                <th style="text-align: right;">Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($services as $service)
                <tr>
                    <td class="col-code">{{ $service->service_code }}</td>
                    <td>{{ $service->customer->name }}</td>
                    <td>{{ $service->service_type }}</td>
                    <td>
                        @php
                            $badgeClass = match(true) {
                                $service->status === 'Selesai' => 'badge-selesai',
                                $service->status === 'Ditolak' => 'badge-ditolak',
                                default => 'badge-proses',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $service->status }}</span>
                    </td>
                    <td class="col-total">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</td>
                    <td>{{ $service->created_at->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 16px;">
                        Tidak ada data servis pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer-note">
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

</body>
</html>