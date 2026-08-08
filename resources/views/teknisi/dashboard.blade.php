<x-dashboardlayout :title="'Dashboard Teknisi'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-teknisi'">

    {{-- Header Sambutan --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Halo, {{ Auth::user()->name }} 👋</h1>
        <p class="text-sm text-gray-500">Berikut ringkasan pekerjaan servis kamu hari ini.</p>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Antrian Baru</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['antrian'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.65 2.65 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437 5.877 5.877"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Sedang Dikerjakan</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['sedang_dikerjakan'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Selesai Bulan Ini</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['selesai_bulan_ini'] }}</p>
            </div>
        </div>
    </div>

    {{-- Tugas Prioritas --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-4 py-3 border-b flex items-center justify-between">
            <p class="font-medium text-sm text-slate-800">Tugas Prioritas (Antrian Terlama)</p>
            <a href="{{ route('teknisi.services.index') }}" class="text-xs text-slate-500 hover:text-slate-800 hover:underline">Lihat semua</a>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($tugasPrioritas as $service)
                    @php
                        $statusColor = match($service->status) {
                            'Menunggu Diperiksa Teknisi' => 'bg-yellow-100 text-yellow-800',
                            'Sedang Diperiksa' => 'bg-blue-100 text-blue-800',
                            'Menunggu Sparepart' => 'bg-orange-100 text-orange-800',
                            'Selesai Diperbaiki', 'Selesai' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $service->service_code }}</td>
                        <td class="px-4 py-3">{{ $service->customer->name }}</td>
                        <td class="px-4 py-3">{{ $service->item_name }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $statusColor }} text-xs px-2 py-1 rounded-full">{{ $service->status }}</span>
                        </td>
                        <td class="px-4 py-3">
    <a href="{{ route('teknisi.services.show', $service->id) }}"
       class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-900 hover:underline">
        Kerjakan
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
        </svg>
    </a>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/>
                                </svg>
                                <span>Tidak ada tugas saat ini.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-dashboardlayout>