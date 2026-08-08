<x-dashboardlayout :title="'Data Sparepart'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Data Sparepart</h2>
        <button type="button" onclick="openAddModal()" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
            + Tambah Sparepart
        </button>
    </div>

    @if (session('error'))
        <div class="mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">Stok</th>
                    <th class="px-4 py-3">Harga Beli</th>
                    <th class="px-4 py-3">Harga Jual</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($spareparts as $sp)
                    <tr>
                        <td class="px-4 py-3">{{ $sp->code }}</td>
                        <td class="px-4 py-3">{{ $sp->name }}</td>
                        <td class="px-4 py-3">{{ $sp->supplier->name ?? '-' }}</td>
                        <td class="px-4 py-3 {{ $sp->stock <= 5 ? 'text-red-600 font-semibold' : '' }}">{{ $sp->stock }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($sp->purchase_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($sp->selling_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="btn-edit-sp inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-800 transition"
                                        title="Edit"
                                        data-action="{{ route('admin.spareparts.update', $sp->id) }}"
                                        data-code="{{ $sp->code }}"
                                        data-name="{{ $sp->name }}"
                                        data-supplier="{{ $sp->supplier_id }}"
                                        data-stock="{{ $sp->stock }}"
                                        data-purchase="{{ $sp->purchase_price }}"
                                        data-selling="{{ $sp->selling_price }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <form id="form-delete-sp-{{ $sp->id }}" method="POST" action="{{ route('admin.spareparts.destroy', $sp->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button"
                                        onclick="confirmAction('form-delete-sp-{{ $sp->id }}', {title: 'Hapus sparepart {{ $sp->name }}?', icon: 'warning', confirmColor: '#dc2626', confirmText: 'Ya, Hapus'})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-800 transition"
                                        title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada data sparepart.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ============ MODAL TAMBAH SPAREPART ============ --}}
    <div id="modal-add" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Tambah Sparepart</h3>
                <button type="button" onclick="closeModal('modal-add')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.spareparts.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Sparepart</label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sparepart</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Supplier <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <select name="supplier_id" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">-- Tanpa Supplier --</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @if ($suppliers->isEmpty())
                        <p class="text-xs text-gray-400 mt-1">Belum ada data supplier.</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" required min="0" class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="selling_price" value="{{ old('selling_price') }}" required min="0" class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal('modal-add')" class="px-4 py-2 rounded-lg text-sm border">Batal</button>
                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ MODAL EDIT SPAREPART ============ --}}
    <div id="modal-edit" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Edit Sparepart</h3>
                <button type="button" onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form id="form-edit-sparepart" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Sparepart</label>
                    <input type="text" name="code" id="edit-code" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sparepart</label>
                    <input type="text" name="name" id="edit-name" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="supplier_id" id="edit-supplier" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">-- Tanpa Supplier --</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <input type="number" name="stock" id="edit-stock" required min="0" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                        <input type="number" name="purchase_price" id="edit-purchase" required min="0" class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="selling_price" id="edit-selling" required min="0" class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal('modal-edit')" class="px-4 py-2 rounded-lg text-sm border">Batal</button>
                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modal-add').classList.remove('hidden');
            document.getElementById('modal-add').classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        document.querySelectorAll('.btn-edit-sp').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('edit-code').value = this.dataset.code;
                document.getElementById('edit-name').value = this.dataset.name;
                document.getElementById('edit-supplier').value = this.dataset.supplier || '';
                document.getElementById('edit-stock').value = this.dataset.stock;
                document.getElementById('edit-purchase').value = this.dataset.purchase;
                document.getElementById('edit-selling').value = this.dataset.selling;
                document.getElementById('form-edit-sparepart').action = this.dataset.action;

                document.getElementById('modal-edit').classList.remove('hidden');
                document.getElementById('modal-edit').classList.add('flex');
            });
        });
    </script>

</x-dashboardlayout>