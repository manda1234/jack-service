<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h2 { margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        td, th { padding: 6px; text-align: left; }
        .border-bottom { border-bottom: 1px solid #ccc; }
        .total-row td { font-weight: bold; border-top: 2px solid #333; }
    </style>
</head>
<body>
    <h2>Jack Service Elektronik</h2>
    <p>Bukittinggi</p>
    <hr>

    <table>
        <tr>
            <td><strong>Kode Service</strong></td>
            <td>{{ $service->service_code }}</td>
            <td><strong>Tanggal</strong></td>
            <td>{{ optional($service->payment)->payment_date?->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td><strong>Pelanggan</strong></td>
            <td>{{ $service->customer->name }}</td>
            <td><strong>Jenis Layanan</strong></td>
            <td>{{ $service->service_type }}</td>
        </tr>
        <tr>
            <td><strong>Barang</strong></td>
            <td>{{ $service->item_name }} {{ $service->brand }}</td>
            <td><strong>Teknisi</strong></td>
            <td>{{ optional($service->technician)->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="border-bottom">
        <thead>
            <tr class="border-bottom">
                <th>Deskripsi</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Biaya Jasa Perbaikan</td>
                <td>Rp {{ number_format($service->service_fee, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td>Biaya Sparepart</td>
                <td>Rp {{ number_format($service->sparepart_cost, 0, ',', '.') }}</td>
            </tr>

            @foreach ($service->spareparts as $usage)
                <tr>
                    <td>Sparepart: {{ $usage->sparepart->name }} x{{ $usage->quantity }}</td>
                    <td>Rp {{ number_format($usage->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>Total</td>
                <td>Rp {{ number_format($service->total_cost, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 20px;">Status Pembayaran: <strong>{{ optional($service->payment)->payment_status ?? 'Unpaid' }}</strong></p>
    <p>Dicatat oleh: {{ optional($service->payment)->paid_by ?? '-' }}</p>
</body>
</html>