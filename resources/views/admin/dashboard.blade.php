<x-dashboardlayout :title="'Dashboard Admin'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    {{-- Kartu Statistik --}}
   <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Menunggu Konfirmasi</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu_konfirmasi'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Sedang Berjalan</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['sedang_berjalan'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Selesai Bulan Ini</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['selesai_bulan_ini'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Pendapatan Bulan Ini</p>
            <p class="text-xl font-bold text-slate-800">Rp {{ number_format($stats['pendapatan_bulan_ini'], 0, ',', '.') }}</p>
        </div>
    </div>

   <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Grafik Pendapatan --}}
        <div class="col-span-2 bg-white rounded-xl shadow p-6">
            <p class="font-medium text-sm mb-4">Pendapatan 6 Bulan Terakhir</p>
            <canvas id="chartPendapatan" height="100"></canvas>
        </div>

        {{-- Stok Sparepart Menipis --}}
        {{-- <div class="bg-white rounded-xl shadow p-6">
            <p class="font-medium text-sm mb-4">⚠️ Stok Sparepart Menipis</p>
            @forelse ($sparepartMenipis as $sp)
                <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                    <span>{{ $sp->name }}</span>
                    <span class="text-red-600 font-semibold">{{ $sp->stock }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Semua stok aman.</p>
            @endforelse
        </div> --}}
    </div>

    {{-- Servis Terbaru --}}
    <div class="bg-white rounded-xl shadow overflow-hidden mt-6">
        <div class="px-4 py-3 border-b flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    <p class="font-medium text-sm">Permintaan Servis Terbaru</p>
</div>
        <table class="w-full text-sm text-left">
            <tbody class="divide-y">
                @forelse ($servicesTerbaru as $service)
                    <tr>
                        <td class="px-4 py-3">{{ $service->service_code }}</td>
                        <td class="px-4 py-3">{{ $service->customer->name }}</td>
                        <td class="px-4 py-3">{{ $service->service_type }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">{{ $service->status }}</span>
                        </td>
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
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        new Chart(document.getElementById('chartPendapatan'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($chartData),
                    borderColor: '#1e293b',
                    backgroundColor: 'rgba(30, 41, 59, 0.1)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>

</x-dashboardlayout>