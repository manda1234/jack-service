<x-dashboardlayout :title="'Workshop'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-teknisi'">

    @if (session('success'))
        <div class="mb-4 bg-green-50 text-green-700 text-sm p-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-3 mb-4">
        <form method="GET" action="{{ route('teknisi.services.index') }}" class="flex items-center gap-2">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode service / barang..."
                       class="pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 shadow-sm w-72 focus:outline-none focus:ring-2 focus:ring-slate-400">
            </div>
            <button type="submit" class="px-4 py-2 text-sm bg-slate-700 text-white rounded-xl shadow hover:bg-slate-800 transition">
                Cari
            </button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Service Code</th>
                        <th class="px-4 py-3 font-medium">Pelanggan</th>
                        <th class="px-4 py-3 font-medium">Barang</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($services as $service)
                        @php
                            $statusColor = match($service->status) {
                                'Menunggu' => 'bg-gray-100 text-gray-700',
                                'Diproses', 'Sedang Diperbaiki' => 'bg-blue-100 text-blue-700',
                                'Menunggu Sparepart' => 'bg-orange-100 text-orange-700',
                                'Selesai' => 'bg-green-100 text-green-700',
                                'Dibatalkan' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $service->service_code }}</td>
                            <td class="px-4 py-3">{{ $service->customer->name }}</td>
                            <td class="px-4 py-3">{{ $service->item_name }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $statusColor }} text-xs px-2.5 py-1 rounded-full font-medium">
                                    {{ $service->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('teknisi.services.show', $service->id) }}"
                                   class="inline-block px-3 py-1.5 text-xs font-medium text-white bg-slate-700 rounded-lg hover:bg-slate-800 transition">
                                    Kerjakan
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                                Tidak ada data servis untuk pencarian ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($services, 'links'))
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $services->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</x-dashboardlayout>