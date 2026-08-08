<x-dashboardlayout :title="'Data Supplier'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Data Supplier</h2>
        <button type="button" onclick="openAddModal()" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
            + Tambah Supplier
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
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Telepon</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Jumlah Sparepart</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($suppliers as $sup)
                    <tr>
                        <td class="px-4 py-3">{{ $sup->name }}</td>
                        <td class="px-4 py-3">{{ $sup->phone ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $sup->address ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $sup->spareparts_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="btn-edit-sup inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-800 transition"
                                        title="Edit"
                                        data-action="{{ route('admin.suppliers.update', $sup->id) }}"
                                        data-name="{{ $sup->name }}"
                                        data-phone="{{ $sup->phone }}"
                                        data-address="{{ $sup->address }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <form id="form-delete-sup-{{ $sup->id }}" method="POST" action="{{ route('admin.suppliers.destroy', $sup->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button"
                                        onclick="confirmAction('form-delete-sup-{{ $sup->id }}', {title: 'Hapus supplier {{ $sup->name }}?', icon: 'warning', confirmColor: '#dc2626', confirmText: 'Ya, Hapus'})"
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
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada data supplier.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ============ MODAL TAMBAH SUPPLIER ============ --}}
    <div id="modal-add" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Tambah Supplier</h3>
                <button type="button" onclick="closeModal('modal-add')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.suppliers.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 text-sm">{{ old('address') }}</textarea>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal('modal-add')" class="px-4 py-2 rounded-lg text-sm border">Batal</button>
                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ MODAL EDIT SUPPLIER ============ --}}
    <div id="modal-edit" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Edit Supplier</h3>
                <button type="button" onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form id="form-edit-supplier" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                    <input type="text" name="name" id="edit-name" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" name="phone" id="edit-phone" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" id="edit-address" rows="3" class="w-full rounded-lg border-gray-300 text-sm"></textarea>
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

        document.querySelectorAll('.btn-edit-sup').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('edit-name').value = this.dataset.name;
                document.getElementById('edit-phone').value = this.dataset.phone;
                document.getElementById('edit-address').value = this.dataset.address;
                document.getElementById('form-edit-supplier').action = this.dataset.action;

                document.getElementById('modal-edit').classList.remove('hidden');
                document.getElementById('modal-edit').classList.add('flex');
            });
        });
    </script>

</x-dashboardlayout>