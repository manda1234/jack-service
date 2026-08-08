<x-dashboardlayout :title="'Data Pembayaran'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">


    {{-- Section: Menunggu Verifikasi (QRIS) --}}
    @php
        $pendingVerification = \App\Models\Payment::with('service.customer')
            ->where('payment_status', 'Menunggu Verifikasi')
            ->latest()
            ->get();
    @endphp

    @if ($pendingVerification->isNotEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 md:p-6 mb-6">
            <p class="font-medium text-sm text-yellow-800 mb-3">
                ⚠ Pembayaran QRIS Menunggu Verifikasi ({{ $pendingVerification->count() }})
            </p>
            <div class="space-y-2">
                @foreach ($pendingVerification as $payment)
                    <div class="bg-white rounded-lg p-3 flex justify-between items-center text-sm">
                        <div>
                            <p class="font-medium">{{ $payment->service->service_code }} — {{ $payment->service->customer->name }}</p>
                            <p class="text-gray-500 text-xs">Rp {{ number_format($payment->amount, 0, ',', '.') }} via QRIS</p>
                        </div>
                        <form id="form-verify-{{ $payment->id }}" method="POST" action="{{ route('admin.payments.verify', $payment->id) }}">
                            @csrf
                        </form>
                        <button type="button"
                                onclick="confirmAction('form-verify-{{ $payment->id }}', {title: 'Konfirmasi pembayaran ini sudah diterima?', icon: 'question', confirmColor: '#16a34a', confirmText: 'Ya, Konfirmasi'})"
                                class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-700">
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow p-4 md:p-6 mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Dibayar Melalui</label>
                <select name="paid_by" class="rounded-lg border-gray-300 text-sm">
                    <option value="">Semua</option>
                    <option value="Admin" {{ request('paid_by') === 'Admin' ? 'selected' : '' }}>Admin (Workshop)</option>
                    <option value="Teknisi" {{ request('paid_by') === 'Teknisi' ? 'selected' : '' }}>Teknisi (Home Service)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-lg border-gray-300 text-sm">
            </div>
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
                Filter
            </button>
            @if (request()->hasAny(['paid_by', 'start_date', 'end_date']))
            
                <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="bg-white rounded-xl shadow p-4 md:p-6 mb-6">
        <p class="text-sm text-gray-500">Total Pendapatan (sesuai filter)</p>
        <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm text-left min-w-[700px]">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">kode service</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">Jenis Layanan</th>
                    <th class="px-4 py-3">Jumlah</th>
                    <th class="px-4 py-3">Dibayar Melalui</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($payments as $payment)
                    <tr>
                        <td class="px-4 py-3">{{ $payment->service->service_code }}</td>
                        <td class="px-4 py-3">{{ $payment->service->customer->name }}</td>
                        <td class="px-4 py-3">{{ $payment->service->service_type }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">{{ $payment->paid_by }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $payment->payment_date?->format('d-m-Y H:i') }}</td>
                       <td class="px-4 py-3">
    <a href="{{ route('admin.services.show', $payment->service_id) }}" 
       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition"
       title="Detail">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </a>
</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada data pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>

</x-dashboardlayout>