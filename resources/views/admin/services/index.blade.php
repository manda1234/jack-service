<x-dashboardlayout :title="'Verifikasi Permintaan Servis'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    @if (session('success'))
        <div class="mb-4 bg-green-50 text-green-700 text-sm p-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER --}}
    <form method="GET" action="{{ route('admin.services.index') }}" class="mb-4 flex items-center gap-3">
        <select name="service_type" onchange="this.form.submit()"
                class="text-sm border-gray-300 rounded-lg focus:ring-slate-500 focus:border-slate-500">
            <option value="">Semua Jenis Layanan</option>
            <option value="Workshop" {{ request('service_type') === 'Workshop' ? 'selected' : '' }}>Workshop</option>
            <option value="Home Service" {{ request('service_type') === 'Home Service' ? 'selected' : '' }}>Home Service</option>
        </select>

        @if (request('service_type'))
            <a href="{{ route('admin.services.index') }}" class="text-sm text-slate-500 hover:text-slate-800">
                Reset
            </a>
        @endif
    </form>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">kode service</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">Jenis Layanan</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Teknisi</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($services as $service)
                    <tr>
                        <td class="px-4 py-3">{{ $service->service_code }}</td>
                        <td class="px-4 py-3">{{ $service->customer->name }}</td>
                        <td class="px-4 py-3">{{ $service->service_type }}</td>
                        <td class="px-4 py-3">{{ $service->item_name }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">{{ $service->status }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $service->technician->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.services.show', $service->id) }}"
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
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada permintaan servis masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $services->appends(request()->query())->links() }}
    </div>

</x-dashboardlayout>