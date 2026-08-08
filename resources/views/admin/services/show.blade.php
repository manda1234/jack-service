
<x-dashboardlayout :title="'Detail Permintaan Servis'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    @if (session('success'))
        <div class="mb-4 bg-green-50 text-green-700 text-sm p-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl bg-white rounded-xl shadow p-6 space-y-4">

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Service Code</p>
                <p class="font-medium">{{ $service->service_code }}</p>
            </div>
            <div>
                <p class="text-gray-500">Status</p>
                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">{{ $service->status }}</span>
            </div>
            @if ($service->status === 'Ditolak' && $service->rejection_reason)
    <div class="bg-red-50 text-red-700 text-sm p-3 rounded-lg">
        <p class="font-medium">Alasan Penolakan:</p>
        <p>{{ $service->rejection_reason }}</p>
    </div>
@endif
            <div>
                <p class="text-gray-500">Pelanggan</p>
                <p class="font-medium">{{ $service->customer->name }} ({{ $service->customer->phone }})</p>
            </div>
            <div>
                <p class="text-gray-500">Jenis Layanan</p>
                <p class="font-medium">{{ $service->service_type }}</p>
            </div>
            <div>
                <p class="text-gray-500">Barang</p>
                <p class="font-medium">{{ $service->item_name }} {{ $service->brand ? '('.$service->brand.')' : '' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Teknisi Ditugaskan</p>
                <p class="font-medium">{{ $service->technician->name ?? '-' }}</p>
            </div>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Keluhan</p>
            <p>{{ $service->complaint }}</p>
        </div>

        @if ($service->photo)
            <div>
                <p class="text-gray-500 text-sm">Foto Bukti Kerusakan</p>
                <a href="{{ $service->photo_url }}" target="_blank">
                    <img src="{{ $service->photo_url }}" alt="Foto kerusakan barang"
                         class="mt-1 rounded-lg border border-gray-200 max-h-64 hover:opacity-90 transition">
                </a>
                <p class="text-xs text-gray-400 mt-1">Klik foto untuk melihat ukuran penuh.</p>
            </div>
        @endif

        @if ($service->address)
            <div>
                <p class="text-gray-500 text-sm">Alamat (Home Service)</p>
                <p>{{ $service->address }}</p>
            </div>
        @endif

        @if ($service->preferred_visit_date)
            <div>
                <p class="text-gray-500 text-sm">Jadwal yang Diinginkan Pelanggan</p>
                <p class="font-medium">
                    {{ $service->preferred_visit_date->format('d-m-Y') }}
                    @if ($service->preferred_visit_time)
                        — {{ $service->preferred_visit_time }}
                    @endif
                </p>
            </div>
        @endif

        @if ($service->scheduled_visit_date)
            <div>
                <p class="text-gray-500 text-sm">Jadwal Kunjungan Terkonfirmasi</p>
                <p class="font-medium text-green-700">
                    {{ $service->scheduled_visit_date->format('d-m-Y') }}
                    @if ($service->scheduled_visit_time)
                        — {{ $service->scheduled_visit_time }}
                    @endif
                </p>
            </div>
        @endif

        <hr>

        {{-- Aksi: Verifikasi awal, hanya muncul kalau masih Menunggu Konfirmasi Admin --}}
      @if ($service->status === 'Menunggu Konfirmasi Admin')
    <div class="space-y-3" x-data="{ showRejectForm: false }">
        <p class="font-medium text-sm">Verifikasi Permintaan</p>

        {{-- Terima & teruskan ke teknisi, tanpa perlu pilih (cuma ada 1 teknisi) --}}
        <form method="POST" action="{{ route('admin.services.assign', $service->id) }}">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                Terima &amp; Teruskan ke Teknisi
            </button>
        </form>

        {{-- Tolak, dengan alasan --}}
        <div x-show="!showRejectForm">
            <button type="button" @click="showRejectForm = true"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                Tolak Permintaan
            </button>
        </div>

        <div x-show="showRejectForm" x-transition class="space-y-2">
            <form method="POST" action="{{ route('admin.services.reject', $service->id) }}" class="space-y-2">
                @csrf
                <label class="block text-sm text-gray-700">Alasan Penolakan</label>
                <textarea name="rejection_reason" rows="2" required
                          placeholder="Contoh: Jenis kerusakan di luar kemampuan perbaikan kami."
                          class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                <div class="flex gap-2">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                        Kirim Penolakan
                    </button>
                    <button type="button" @click="showRejectForm = false" class="px-4 py-2 rounded-lg text-sm border">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

        {{-- Aksi: konfirmasi barang diterima, khusus Workshop --}}
        @if ($service->status === 'Menunggu Barang Diantar' && $service->service_type === 'Workshop')
            <form method="POST" action="{{ route('admin.services.receive', $service->id) }}">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Konfirmasi Barang Diterima
                </button>
            </form>
        @endif

        {{-- Aksi: proses pembayaran, khusus Workshop --}}
     {{-- Aksi: Admin menentukan biaya final, setelah Teknisi selesai perbaikan --}}
@if ($service->status === 'Selesai Diperbaiki')
    <div class="bg-white rounded-xl shadow p-6 space-y-4">
        <p class="font-medium text-sm">Tentukan Biaya Final</p>

        <div class="bg-gray-50 rounded-lg p-3 text-sm space-y-1">
            @if ($service->spareparts->isNotEmpty())
                @foreach ($service->spareparts->where('approval_status', 'Disetujui') as $usage)
                    <div class="flex justify-between text-gray-600">
                        <span>{{ $usage->sparepart->name }} x{{ $usage->quantity }}</span>
                        <span>Rp {{ number_format($usage->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            @else
                <p class="text-gray-400">Tidak ada sparepart yang digunakan.</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.services.setFinalCost', $service->id) }}" class="space-y-3">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Biaya Jasa (Rp)</label>
                    <input type="number" name="service_fee" value="{{ old('service_fee', $service->service_fee) }}"
                           required min="0" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Biaya Sparepart (Rp)</label>
                    <input type="number" name="sparepart_cost" value="{{ old('sparepart_cost', $service->sparepart_cost) }}"
                           required min="0" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
            </div>
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
                Tentukan Biaya &amp; Lanjut ke Pembayaran
            </button>
        </form>
    </div>
@endif

        {{-- Invoice, setelah status Selesai --}}
        @if ($service->status === 'Selesai' && $service->payment)
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
                        <p class="text-gray-500">Dibayar Melalui</p>
                        <p class="font-medium">{{ $service->payment->paid_by }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Pembayaran</p>
                        <p class="font-medium">{{ $service->payment->payment_date?->format('d-m-Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status Pembayaran</p>
                        <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                            {{ $service->payment->payment_status }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('invoice.download', $service->id) }}"
                   class="inline-block bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700 mt-2">
                    Unduh Invoice PDF
                </a>
            </div>
        @endif

    </div>

</x-dashboardlayout>