@if ($service->status === 'Selesai' && $service->payment)
    <hr>
    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
        <p class="font-medium text-sm">Invoice</p>

        @if ($service->spareparts->isNotEmpty())
            <table class="w-full text-sm">
                <thead class="text-gray-500 text-left">
                    <tr>
                        <th class="pb-2">Sparepart</th>
                        <th class="pb-2">Qty</th>
                        <th class="pb-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($service->spareparts as $usage)
                        <tr class="border-t">
                            <td class="py-1">{{ $usage->sparepart->name }}</td>
                            <td class="py-1">{{ $usage->quantity }}</td>
                            <td class="py-1 text-right">Rp {{ number_format($usage->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="grid grid-cols-2 gap-3 text-sm border-t pt-3">
            <div>
                <p class="text-gray-500">Biaya Jasa</p>
                <p class="font-medium">Rp {{ number_format($service->service_fee, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Biaya Sparepart</p>
                <p class="font-medium">Rp {{ number_format($service->sparepart_cost, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Total Dibayar</p>
                <p class="font-semibold text-base">Rp {{ number_format($service->payment->amount, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal Pembayaran</p>
                <p class="font-medium">{{ $service->payment->payment_date?->format('d-m-Y H:i') }}</p>
            </div>
        </div>

        <a href="{{ route('invoice.download', $service->id) }}"
           class="inline-flex items-center gap-2 bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700 mt-2">
            @if (function_exists('sidebarIcon'))
                {!! sidebarIcon('document-plus', 'w-4 h-4') !!}
            @endif
            Unduh Invoice PDF
        </a>
    </div>
@elseif ($service->status === 'Selesai Diperbaiki' && $service->service_type === 'Workshop')
    <div class="bg-blue-50 text-blue-700 text-sm p-3 rounded-lg">
        Barang Anda sudah selesai diperbaiki. Silakan datang ke toko untuk mengambil barang dan melakukan pembayaran.
    </div>
@elseif ($service->status === 'Selesai Diperbaiki' && $service->service_type === 'Home Service')
    <div class="bg-blue-50 text-blue-700 text-sm p-3 rounded-lg">
        Perbaikan sudah selesai. Silakan lakukan pembayaran tunai langsung kepada teknisi di lokasi Anda.
        Invoice akan tersedia setelah teknisi mencatat pembayaran ke sistem.
    </div>
@endif