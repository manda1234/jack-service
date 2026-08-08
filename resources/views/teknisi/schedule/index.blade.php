<x-dashboardlayout :title="'Jadwal Home Service'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-teknisi'">

    @php
        $statusColor = fn($s) => match($s) {
            'Dijadwalkan' => 'bg-indigo-100 text-indigo-700',
            'Sedang Berjalan' => 'bg-blue-100 text-blue-700',
            'Selesai' => 'bg-green-100 text-green-700',
            'Dibatalkan' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };

        $getDate = fn($item) => $item->scheduled_visit_date ?? $item->preferred_visit_date;
        $getTime = fn($item) => $item->scheduled_visit_time ?? $item->preferred_visit_time;

        $todayTotal   = $jadwal->filter(fn($i) => \Carbon\Carbon::parse($getDate($i))->isToday())->count();
        $todayOngoing = $jadwal->filter(fn($i) => \Carbon\Carbon::parse($getDate($i))->isToday() && $i->status === 'Sedang Berjalan')->count();
        $todayDone    = $jadwal->filter(fn($i) => \Carbon\Carbon::parse($getDate($i))->isToday() && $i->status === 'Selesai')->count();

        $grouped = $jadwal->groupBy(fn ($item) => \Carbon\Carbon::parse($getDate($item))->translatedFormat('l, d F Y'));
    @endphp

    {{-- Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Kunjungan Hari Ini</p>
                <p class="text-xl font-semibold text-gray-800">{{ $todayTotal }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Sedang Berjalan</p>
                <p class="text-xl font-semibold text-gray-800">{{ $todayOngoing }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Selesai</p>
                <p class="text-xl font-semibold text-gray-800">{{ $todayDone }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Tanggal --}}
    <form method="GET" action="{{ route('teknisi.schedule.index') }}" class="flex items-center gap-2 mb-6">
        <input type="date" name="date" value="{{ request('date') }}"
               class="px-3 py-2 text-sm rounded-xl border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
        <button type="submit" class="px-4 py-2 text-sm bg-slate-700 text-white rounded-xl shadow hover:bg-slate-800 transition">
            Filter
        </button>
        @if (request('date'))
            <a href="{{ route('teknisi.schedule.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
        @endif
    </form>

    {{-- Jadwal per Hari --}}
    @forelse ($grouped as $tanggalLabel => $items)
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $tanggalLabel }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($items->sortBy(fn($i) => $getTime($i)) as $item)
                    <div class="bg-white rounded-xl shadow hover:shadow-md transition p-4 flex flex-col gap-3">

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-slate-600 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex items-center gap-1.5 text-slate-600 text-sm font-medium">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ $getTime($item) }}
</div>
                            </div>
                            <span class="{{ $statusColor($item->status) }} text-xs px-2.5 py-1 rounded-full font-medium">
                                {{ $item->status }}
                            </span>
                        </div>

                        <div class="text-xs text-gray-400">
                            {{ $item->service_code }}
                            @if (is_null($item->scheduled_visit_date))
                                <span class="ml-1 text-orange-500">(belum di-fix, jadwal permintaan awal)</span>
                            @endif
                        </div>

                        <div class="space-y-1 text-sm text-gray-700">
                            <p><span class="text-gray-500">Pelanggan:</span> {{ $item->customer->name }}</p>
                            <p><span class="text-gray-500">No. HP:</span> {{ $item->customer->phone }}</p>
                            <p><span class="text-gray-500">Barang:</span> {{ $item->item_name }} {{ $item->brand ? '('.$item->brand.')' : '' }}</p>
                            <p class="flex items-start gap-1.5">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $item->address }}</span>
                            </p>
                        </div>

                        <a href="{{ route('teknisi.services.show', $item->id) }}"
                           class="mt-2 text-center text-xs font-medium text-white bg-slate-700 rounded-lg py-2 hover:bg-slate-800 transition">
                            Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow p-10 text-center text-gray-400">
            Belum ada jadwal Home Service.
        </div>
    @endforelse

</x-dashboardlayout>