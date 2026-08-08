<x-dashboardlayout :title="'Detail Servis'" :userName="Auth::guard('customer')->user()->name" :logoutRoute="route('pelanggan.logout')" :sidebar="'layouts.navigation-pelanggan'">

    <div class="max-w-2xl bg-white rounded-xl shadow p-6 space-y-4">

        @if (session('success'))
            <div class="bg-green-50 text-green-700 text-sm p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div>
            <p class="text-sm text-gray-500">Kode Service</p>
            <p class="text-lg font-semibold">{{ $service->service_code }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Status Saat Ini</p>
            <span class="inline-block bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
                {{ $service->status }}
            </span>
        </div>
@if (trim($service->status) === 'Menunggu Pembayaran')
            <div class="bg-yellow-50 rounded-lg p-4 flex justify-between items-center">
                <div>
                    <p class="font-medium text-sm text-yellow-800">Servis Anda Siap Dibayar</p>
                    <p class="text-sm text-yellow-700">
                        Total tagihan: <span class="font-semibold">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</span>
                    </p>
                </div>
                <a href="{{ route('pelanggan.services.payment', $service->id) }}"
                   class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700 whitespace-nowrap">
                    Bayar Sekarang
                </a>
            </div>
        @endif

        @if ($service->status === 'Ditolak' && $service->rejection_reason)
            <div class="bg-red-50 text-red-700 text-sm p-3 rounded-lg">
                <p class="font-medium">Alasan Penolakan:</p>
                <p>{{ $service->rejection_reason }}</p>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Jenis Layanan</p>
                <p class="font-medium">{{ $service->service_type }}</p>
            </div>
            <div>
                <p class="text-gray-500">Jenis Barang</p>
                <p class="font-medium">{{ $service->item_name }} {{ $service->brand ? '('.$service->brand.')' : '' }}</p>
            </div>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Keluhan</p>
            <p>{{ $service->complaint }}</p>
        </div>

        @if ($service->spareparts->where('approval_status', 'Menunggu Persetujuan')->isNotEmpty())
            <div class="bg-yellow-50 rounded-lg p-4 space-y-3">
                <p class="font-medium text-sm text-yellow-800">Persetujuan Penggantian Sparepart</p>

                @foreach ($service->spareparts->where('approval_status', 'Menunggu Persetujuan') as $usage)
                    <div class="bg-white rounded-lg p-3 space-y-2">
                        <p class="text-sm font-medium">
                            {{ $usage->sparepart->name }} x{{ $usage->quantity }} — Rp {{ number_format($usage->subtotal, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">Alasan teknisi: {{ $usage->reason }}</p>

                        <div class="flex gap-2">
                            <form id="form-approve-{{ $usage->id }}" method="POST" action="{{ route('pelanggan.spareparts.approve', $usage->id) }}">
                                @csrf
                            </form>
                            <button type="button"
                                    onclick="confirmAction('form-approve-{{ $usage->id }}', {title: 'Setujui penggantian sparepart ini?', icon: 'question', confirmColor: '#16a34a', confirmText: 'Ya, Setuju'})"
                                    class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-700">
                                Setujui
                            </button>

                            <form id="form-reject-{{ $usage->id }}" method="POST" action="{{ route('pelanggan.spareparts.reject', $usage->id) }}">
                                @csrf
                            </form>
                            <button type="button"
                                    onclick="confirmAction('form-reject-{{ $usage->id }}', {title: 'Tolak penggantian sparepart ini?', icon: 'warning', confirmColor: '#dc2626', confirmText: 'Ya, Tolak'})"
                                    class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700">
                                Tolak
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($service->photo)
            <div>
                <p class="text-gray-500 text-sm">Foto Bukti Kerusakan</p>
                <a href="{{ $service->photo_url }}" target="_blank">
                    <img src="{{ $service->photo_url }}" alt="Foto kerusakan barang"
                         class="mt-1 rounded-lg border border-gray-200 max-h-64 hover:opacity-90 transition">
                </a>
            </div>
        @endif

        @if ($service->address)
            <div>
                <p class="text-gray-500 text-sm">Alamat</p>
                <p>{{ $service->address }}</p>
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

        @include('partials.invoice-summary', ['service' => $service])

    </div>

</x-dashboardlayout>