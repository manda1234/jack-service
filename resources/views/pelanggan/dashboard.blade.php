<x-dashboardlayout :title="'Dashboard Pelanggan'" :userName="Auth::guard('customer')->user()->name" :logoutRoute="route('pelanggan.logout')" :sidebar="'layouts.navigation-pelanggan'">

    {{-- Kartu ringkasan angka --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Servis</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Sedang Berjalan</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['berjalan'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Selesai</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    {{-- Kartu status servis aktif --}}
    @if ($activeService)
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Servis Aktif — {{ $activeService->service_code }}</p>
                    <p class="font-semibold text-lg">{{ $activeService->item_name }} {{ $activeService->brand ? '('.$activeService->brand.')' : '' }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $activeService->service_type }}</p>
                </div>
                <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
                    {{ $activeService->status }}
                </span>
            </div>
            <a href="{{ route('pelanggan.services.show', $activeService->id) }}"
               class="inline-block mt-4 text-sm text-slate-700 hover:underline">
                Lihat Detail &rarr;
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-6 mb-6 text-center">
            <p class="text-gray-500 mb-3">Belum ada servis yang sedang berjalan.</p>
            <a href="{{ route('pelanggan.services.create') }}"
               class="inline-block bg-slate-800 text-white px-5 py-2 rounded-lg text-sm hover:bg-slate-700">
                Ajukan Servis Baru
            </a>
        </div>
    @endif

    {{-- Riwayat terakhir --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-4 py-3 border-b flex justify-between items-center">
            <p class="font-medium text-sm">Servis Terakhir</p>
            <a href="{{ route('pelanggan.services.index') }}" class="text-xs text-slate-600 hover:underline">Lihat semua</a>
        </div>
        <table class="w-full text-sm text-left">
            <tbody class="divide-y">
                @forelse ($recentServices as $service)
                    <tr>
                        <td class="px-4 py-3">{{ $service->service_code }}</td>
                        <td class="px-4 py-3">{{ $service->item_name }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">{{ $service->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('pelanggan.services.show', $service->id) }}" class="text-slate-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada riwayat servis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-dashboardlayout>