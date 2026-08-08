<x-dashboardlayout :title="'Laporan Servis & Pendapatan'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $start }}" class="rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $end }}" class="rounded-lg border-gray-300 text-sm">
            </div>
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
                Filter
            </button>

            <div class="ml-auto flex gap-2">
                <a href="{{ route('admin.reports.exportPdf', request()->query()) }}"
                   class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.exportExcel', request()->query()) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Total Servis</p>
            <p class="text-xl font-bold">{{ $summary['total_servis'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Selesai</p>
            <p class="text-xl font-bold text-green-600">{{ $summary['selesai'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Berjalan</p>
            <p class="text-xl font-bold text-yellow-600">{{ $summary['berjalan'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Ditolak</p>
            <p class="text-xl font-bold text-red-600">{{ $summary['ditolak'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Pendapatan</p>
            <p class="text-lg font-bold">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3"> kode service</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">Layanan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($services as $service)
                    <tr>
                        <td class="px-4 py-3">{{ $service->service_code }}</td>
                        <td class="px-4 py-3">{{ $service->customer->name }}</td>
                        <td class="px-4 py-3">{{ $service->service_type }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">{{ $service->status }}</span>
                        </td>
                        <td class="px-4 py-3">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $service->created_at->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-dashboardlayout>