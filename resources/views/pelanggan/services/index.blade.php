<x-dashboardlayout :title="'Riwayat Servis'" :userName="Auth::guard('customer')->user()->name" :logoutRoute="route('pelanggan.logout')" :sidebar="'layouts.navigation-pelanggan'">



    <div x-data="serviceModal()" x-cloak>

        {{-- Filter --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h1 class="text-lg font-semibold text-slate-800"></h1>

            <form method="GET" action="{{ route('pelanggan.services.index') }}" class="flex items-center gap-2">
                <select name="service_type" onchange="this.form.submit()"
                        class="text-sm rounded-xl border-gray-200 shadow-sm focus:ring-2 focus:ring-slate-400 focus:border-slate-400">
                    <option value="">Semua Jenis Layanan</option>
                    <option value="Workshop" {{ request('service_type') === 'Workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="Home Service" {{ request('service_type') === 'Home Service' ? 'selected' : '' }}>Home Service</option>
                </select>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 font-medium">Service Code</th>
                            <th class="px-4 py-3 font-medium">Jenis Layanan</th>
                            <th class="px-4 py-3 font-medium">Barang</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($services as $service)
                            @php
                                $statusColor = match($service->status) {
                                    'Menunggu Diperiksa Teknisi', 'Menunggu Kunjungan Teknisi' => 'bg-gray-100 text-gray-700',
                                    'Sedang Diperiksa', 'Sedang Diperbaiki' => 'bg-blue-100 text-blue-700',
                                    'Menunggu Sparepart' => 'bg-orange-100 text-orange-700',
                                    'Menunggu Persetujuan' => 'bg-yellow-100 text-yellow-800',
                                    'Selesai Diperbaiki', 'Selesai' => 'bg-green-100 text-green-700',
                                    'Dibatalkan' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };

                                $typeColor = $service->service_type === 'Home Service'
                                    ? 'bg-purple-100 text-purple-700'
                                    : 'bg-slate-100 text-slate-700';
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $service->service_code }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $typeColor }} text-xs px-2.5 py-1 rounded-full font-medium">
                                        {{ $service->service_type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $service->item_name }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $statusColor }} text-xs px-2.5 py-1 rounded-full font-medium">
                                        {{ $service->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button"
                                            @click="openModal('{{ route('pelanggan.services.show', $service->id) }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-slate-700 rounded-lg hover:bg-slate-800 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                                    Belum ada riwayat servis{{ request('service_type') ? ' untuk filter ini' : '' }}.
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

        {{-- Modal Detail Servis --}}
        <div x-show="show"
             x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
             style="display: none;">
            <div @click.outside="closeModal()"
                 x-transition
                 class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
                    <h2 class="text-base font-semibold text-slate-800">Detail Servis</h2>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <template x-if="loading">
                        <p class="text-sm text-gray-400 text-center py-10">Memuat detail...</p>
                    </template>
                    <template x-if="error">
                        <p class="text-sm text-red-500 text-center py-10">Gagal memuat detail. Coba lagi.</p>
                    </template>
                    <div x-show="!loading && !error" x-html="content"></div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function serviceModal() {
            return {
                show: false,
                loading: false,
                error: false,
                content: '',

                openModal(url) {
                    this.show = true;
                    this.loading = true;
                    this.error = false;
                    this.content = '';

                    fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => {
                            if (!res.ok) throw new Error('Request failed');
                            return res.text();
                        })
                        .then(html => {
                            this.content = html;
                            this.loading = false;
                        })
                        .catch(() => {
                            this.error = true;
                            this.loading = false;
                        });
                },

                closeModal() {
                    this.show = false;
                }
            }
        }

        function confirmAction(formId, options = {}) {
            Swal.fire({
                title: options.title || 'Apakah kamu yakin?',
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonColor: options.confirmColor || '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: options.confirmText || 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    @endpush

</x-dashboardlayout>