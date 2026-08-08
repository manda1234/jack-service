<x-dashboardlayout :title="'Kerjakan Servis'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-teknisi'">

    @if (session('success'))
        <div class="mb-4 bg-green-50 text-green-700 text-sm p-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="max-w-2xl space-y-6">

        {{-- Info servis --}}
        <div class="bg-white rounded-xl shadow p-6 space-y-3">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">{{ $service->service_code }}</p>
                    <p class="font-semibold">{{ $service->customer->name }} — {{ $service->item_name }} {{ $service->brand ? '('.$service->brand.')' : '' }}</p>
                </div>
                <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">{{ $service->status }}</span>
            </div>
            <p class="text-sm text-gray-500">Keluhan awal:</p>
            <p class="text-sm">{{ $service->complaint }}</p>
            @if ($service->address)
                <p class="text-sm text-gray-500">Alamat: {{ $service->address }}</p>
            @endif
        </div>

        {{-- Tombol konfirmasi teknisi sudah sampai (khusus Home Service) --}}
        @if ($service->service_type === 'Home Service' && $service->status === 'Menunggu Kunjungan Teknisi')
            <form method="POST" action="{{ route('teknisi.services.arrive', $service->id) }}">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Saya Sudah Sampai di Lokasi
                </button>
            </form>
        @endif

        {{-- Konfirmasi jadwal kunjungan (khusus Home Service) --}}
        @if ($service->service_type === 'Home Service' && $service->status === 'Menunggu Kunjungan Teknisi')
            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <p class="font-medium text-sm">Konfirmasi Jadwal Kunjungan</p>

                @if ($service->scheduled_visit_date)
                    <div class="bg-green-50 text-green-700 text-sm p-3 rounded-lg">
                        Jadwal terkonfirmasi: <strong>{{ $service->scheduled_visit_date->format('d-m-Y') }}</strong>, {{ $service->scheduled_visit_time }}
                    </div>
                @else
                    <form method="POST" action="{{ route('teknisi.services.confirmSchedule', $service->id) }}" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Tanggal Kunjungan</label>
                                <input type="date" name="scheduled_visit_date"
                                       value="{{ old('scheduled_visit_date', $service->preferred_visit_date?->format('Y-m-d')) }}"
                                       min="{{ now()->format('Y-m-d') }}" required
                                       class="w-full rounded-lg border-gray-300 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Waktu Kunjungan</label>
                                <select name="scheduled_visit_time" required class="w-full rounded-lg border-gray-300 text-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="Pagi (08.00-11.00)" {{ $service->preferred_visit_time === 'Pagi (08.00-11.00)' ? 'selected' : '' }}>Pagi (08.00 - 11.00)</option>
                                    <option value="Siang (11.00-14.00)" {{ $service->preferred_visit_time === 'Siang (11.00-14.00)' ? 'selected' : '' }}>Siang (11.00 - 14.00)</option>
                                    <option value="Sore (14.00-17.00)" {{ $service->preferred_visit_time === 'Sore (14.00-17.00)' ? 'selected' : '' }}>Sore (14.00 - 17.00)</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
                            Konfirmasi Jadwal
                        </button>
                    </form>

                    @if ($service->preferred_visit_date)
                        <div class="bg-blue-50 text-blue-700 text-sm p-3 rounded-lg">
                            Preferensi pelanggan: <strong>{{ $service->preferred_visit_date->format('d-m-Y') }}</strong>, {{ $service->preferred_visit_time }}
                        </div>
                    @endif
                @endif
            </div>
        @endif

        {{-- Mulai periksa --}}
        @if (in_array($service->status, ['Menunggu Diperiksa Teknisi']))
            <form method="POST" action="{{ route('teknisi.services.startCheck', $service->id) }}">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Mulai Periksa Barang
                </button>
            </form>
        @endif

        {{-- Form diagnosa --}}
        @if (in_array($service->status, ['Sedang Diperiksa', 'Menunggu Sparepart']))
            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <p class="font-medium text-sm">Diagnosa &amp; Tindakan</p>
                <form method="POST" action="{{ route('teknisi.services.diagnosis', $service->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Diagnosa Kerusakan</label>
                        <textarea name="diagnosis" rows="3" required class="w-full rounded-lg border-gray-300 text-sm">{{ old('diagnosis', $service->diagnosis) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Tindakan Perbaikan</label>
                        <textarea name="repair_action" rows="3" class="w-full rounded-lg border-gray-300 text-sm">{{ old('repair_action', $service->repair_action) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Biaya Jasa (Rp)</label>
                        <input type="number" name="service_fee" value="{{ old('service_fee', $service->service_fee) }}" required class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
                        Simpan Diagnosa
                    </button>
                </form>
            </div>

            {{-- Form tambah sparepart / komponen kecil --}}
            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <p class="font-medium text-sm">Ajukan Penggantian Sparepart / Komponen</p>

                <form method="POST" action="{{ route('teknisi.services.sparepart', $service->id) }}" class="space-y-3" id="replacement-form">
                    @csrf

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Jenis Penggantian</label>
                        <select name="replacement_type" id="replacement_type" class="w-full rounded-lg border-gray-300 text-sm">
                            <option value="sparepart">Sparepart Utama</option>
                            <option value="component">Komponen Kecil (Termasuk Biaya Jasa)</option>
                        </select>
                    </div>

                    {{-- Sparepart Utama (repeater, bisa lebih dari 1 item) --}}
                    <div id="sparepart-fields" class="space-y-2">
                        <div id="sparepart-rows" class="space-y-2"></div>
                        <button type="button" id="add-sparepart"
                                class="text-xs text-slate-700 border border-slate-300 px-3 py-1 rounded-lg hover:bg-slate-50">
                            + Tambah Sparepart
                        </button>
                    </div>

                    {{-- Komponen Kecil (repeater) --}}
                    <div id="component-fields" class="space-y-2 hidden">
                        <div id="component-rows" class="space-y-2"></div>
                        <button type="button" id="add-component"
                                class="text-xs text-slate-700 border border-slate-300 px-3 py-1 rounded-lg hover:bg-slate-50">
                            + Tambah Komponen
                        </button>
                    </div>

                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
                        Ajukan
                    </button>
                </form>

                <template id="sparepart-row-template">
                    <div class="flex gap-2 items-start sparepart-row">
                        <select class="rounded-lg border-gray-300 text-sm flex-1" data-field="sparepart_id">
                            <option value="">-- Pilih Sparepart --</option>
                            @foreach ($spareparts as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->name }} (stok: {{ $sp->stock }})</option>
                            @endforeach
                        </select>
                        <input type="number" placeholder="Qty" min="1" value="1" class="w-16 rounded-lg border-gray-300 text-sm" data-field="quantity">
                        <input type="text" placeholder="Alasan penggantian" class="rounded-lg border-gray-300 text-sm flex-1" data-field="reason">
                        <button type="button" class="remove-sparepart text-red-500 text-xs px-2">Hapus</button>
                    </div>
                </template>

                <template id="component-row-template">
                    <div class="flex gap-2 items-start component-row">
                        <input type="text" placeholder="Nama Komponen" class="rounded-lg border-gray-300 text-sm flex-1" data-field="name">
                        <input type="number" placeholder="Qty" min="1" value="1" class="w-16 rounded-lg border-gray-300 text-sm" data-field="quantity">
                        <input type="text" placeholder="Keterangan (opsional)" class="rounded-lg border-gray-300 text-sm flex-1" data-field="note">
                        <button type="button" class="remove-component text-red-500 text-xs px-2">Hapus</button>
                    </div>
                </template>

                @if ($service->spareparts->isNotEmpty())
                    <table class="w-full text-sm mt-3">
                        <thead class="text-gray-500 text-left">
                            <tr>
                                <th>Jenis Penggantian</th>
                                <th>Nama Item</th>
                                <th>Qty</th>
                                <th>Biaya</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($service->spareparts as $usage)
                                <tr class="border-t">
                                    <td class="py-2 text-xs">
                                        {{ $usage->replacement_type === 'component' ? 'Komponen Kecil' : 'Sparepart Utama' }}
                                    </td>
                                    <td class="py-2">
                                        {{ $usage->replacement_type === 'component' ? $usage->component_name : $usage->sparepart->name }}
                                    </td>
                                    <td class="py-2">{{ $usage->quantity }}</td>
                                    <td class="py-2">
                                        @if ($usage->replacement_type === 'component')
                                            <span class="text-xs text-gray-500">Termasuk Biaya Jasa</span>
                                        @else
                                            Rp {{ number_format($usage->subtotal, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        @if ($usage->replacement_type === 'component')
                                            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">Termasuk Jasa</span>
                                        @elseif ($usage->approval_status === 'Disetujui')
                                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Disetujui</span>
                                        @elseif ($usage->approval_status === 'Ditolak')
                                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">Ditolak</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Menunggu Persetujuan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Selesaikan --}}
            <form method="POST" action="{{ route('teknisi.services.complete', $service->id) }}"
                  onsubmit="return confirm('Yakin tandai servis ini selesai diperbaiki?')">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                    Tandai Selesai Diperbaiki
                </button>
            </form>
        @endif

        @if ($service->status === 'Selesai Diperbaiki')
            <div class="bg-blue-50 text-blue-700 text-sm p-4 rounded-lg">
                Servis sudah selesai diperbaiki. Admin akan menentukan biaya final, kemudian pelanggan melakukan pembayaran melalui sistem.
            </div>
        @elseif (in_array($service->status, ['Menunggu Pembayaran', 'Selesai']))
            <div class="bg-gray-50 text-gray-600 text-sm p-4 rounded-lg">
                Servis ini sudah masuk tahap pembayaran, tidak ada tindakan lebih lanjut yang diperlukan dari Teknisi.
            </div>
        @endif
    </div>

    @if (in_array($service->status, ['Sedang Diperiksa', 'Menunggu Sparepart']))
    <script>
    (function () {
        const typeSelect = document.getElementById('replacement_type');

        // --- Sparepart Utama (repeater) ---
        const sparepartFields = document.getElementById('sparepart-fields');
        const sparepartRows = document.getElementById('sparepart-rows');
        const addSparepartBtn = document.getElementById('add-sparepart');
        const sparepartTemplate = document.getElementById('sparepart-row-template');
        let sparepartIndex = 0;

        function addSparepartRow() {
            const clone = sparepartTemplate.content.cloneNode(true);
            const row = clone.querySelector('.sparepart-row');

            row.querySelectorAll('[data-field]').forEach(input => {
                const field = input.getAttribute('data-field');
                input.name = `spareparts[${sparepartIndex}][${field}]`;
                input.required = true;
            });

            row.querySelector('.remove-sparepart').addEventListener('click', () => {
                row.remove();
                // pastikan minimal selalu ada 1 baris saat mode sparepart aktif
                if (sparepartRows.children.length === 0 && typeSelect.value === 'sparepart') {
                    addSparepartRow();
                }
            });

            sparepartRows.appendChild(row);
            sparepartIndex++;
        }

        addSparepartBtn.addEventListener('click', addSparepartRow);

        // --- Komponen Kecil (repeater) ---
        const componentFields = document.getElementById('component-fields');
        const componentRows = document.getElementById('component-rows');
        const addComponentBtn = document.getElementById('add-component');
        const componentTemplate = document.getElementById('component-row-template');
        let componentIndex = 0;

        function addComponentRow() {
            const clone = componentTemplate.content.cloneNode(true);
            const row = clone.querySelector('.component-row');

            row.querySelectorAll('[data-field]').forEach(input => {
                const field = input.getAttribute('data-field');
                input.name = `components[${componentIndex}][${field}]`;
                if (field !== 'note') input.required = true;
            });

            row.querySelector('.remove-component').addEventListener('click', () => {
                row.remove();
                if (componentRows.children.length === 0 && typeSelect.value === 'component') {
                    addComponentRow();
                }
            });

            componentRows.appendChild(row);
            componentIndex++;
        }

        addComponentBtn.addEventListener('click', addComponentRow);

        // --- Toggle antara mode sparepart / komponen ---
        function toggleFields() {
            if (typeSelect.value === 'component') {
                sparepartFields.classList.add('hidden');
                componentFields.classList.remove('hidden');

                sparepartRows.querySelectorAll('[data-field]').forEach(i => i.required = false);

                if (componentRows.children.length === 0) addComponentRow();
            } else {
                componentFields.classList.add('hidden');
                sparepartFields.classList.remove('hidden');

                componentRows.querySelectorAll('[data-field]').forEach(i => i.required = false);

                if (sparepartRows.children.length === 0) addSparepartRow();
            }
        }

        typeSelect.addEventListener('change', toggleFields);

        toggleFields();
    })();
    </script>
    @endif

</x-dashboardlayout>