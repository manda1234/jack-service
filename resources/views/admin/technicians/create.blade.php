<x-dashboardlayout :title="'Data Teknisi'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Data Teknisi</h2>
        <button type="button" onclick="openAddModal()" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
            + Tambah Teknisi
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
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Servis Aktif</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($technicians as $tech)
                    <tr>
                        <td class="px-4 py-3">{{ $tech->name }}</td>
                        <td class="px-4 py-3">{{ $tech->email }}</td>
                        <td class="px-4 py-3">{{ $tech->services()->whereNotIn('status', ['Selesai', 'Ditolak'])->count() }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        onclick="openEditModal({{ $tech->id }}, '{{ $tech->name }}', '{{ $tech->email }}', '{{ route('admin.technicians.update', $tech->id) }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-800 transition"
                                        title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <form id="form-delete-{{ $tech->id }}" method="POST" action="{{ route('admin.technicians.destroy', $tech->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button"
                                        onclick="confirmAction('form-delete-{{ $tech->id }}', {title: 'Hapus teknisi {{ $tech->name }}?', icon: 'warning', confirmColor: '#dc2626', confirmText: 'Ya, Hapus'})"
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
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada data teknisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ============ MODAL TAMBAH TEKNISI ============ --}}
    <div id="modal-add" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Tambah Teknisi</h3>
                <button type="button" onclick="closeModal('modal-add')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.technicians.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required minlength="8" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal('modal-add')" class="px-4 py-2 rounded-lg text-sm border">Batal</button>
                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ MODAL EDIT TEKNISI ============ --}}
    <div id="modal-edit" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Edit Teknisi</h3>
                <button type="button" onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form id="form-edit-technician" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" id="edit-name" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit-email" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" minlength="8" class="w-full rounded-lg border-gray-300 text-sm">
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

        function openEditModal(id, name, email, actionUrl) {
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('form-edit-technician').action = actionUrl;
            document.getElementById('modal-edit').classList.remove('hidden');
            document.getElementById('modal-edit').classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        // Kalau ada error validasi (misal setelah submit modal add/edit gagal), buka modal-nya otomatis
        @if ($errors->any() && old('name'))
            document.addEventListener('DOMContentLoaded', function () {
                openAddModal();
            });
        @endif
    </script>

</x-dashboardlayout>