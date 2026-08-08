<x-dashboardlayout title="Data Pelanggan">

    <div class="bg-white rounded-lg shadow-sm">

        {{-- HEADER: SEARCH ONLY --}}
        <div class="p-6 border-b">
            <form method="GET" class="flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama / no. HP..."
                    class="border rounded-md px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-slate-700"
                >
                <button class="bg-slate-800 text-white text-sm px-4 py-2 rounded-md hover:bg-slate-700">
                    Cari
                </button>
                @if(request('search'))
                   <a href="{{ route('admin.customers.index') }}"
                       class="text-sm text-gray-600 px-4 py-2 rounded-md border hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-left">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">No. HP</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3">Terdaftar</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $customer->name }}</td>
                            <td class="px-6 py-3">{{ $customer->phone }}</td>
                            <td class="px-6 py-3">{{ $customer->email ?? '-' }}</td>
                            <td class="px-6 py-3">{{ $customer->address ?? '-' }}</td>
                            <td class="px-6 py-3">{{ $customer->created_at->format('d-m-Y') }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button"
                                            onclick="openEditModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ addslashes($customer->phone) }}', '{{ addslashes($customer->email) }}', '{{ addslashes($customer->address) }}', '{{ route('admin.customers.update', $customer) }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-800 transition"
                                            title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <button type="button"
                                            onclick="openDeleteModal('{{ addslashes($customer->name) }}', '{{ route('admin.customers.destroy', $customer) }}')"
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
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                Belum ada data pelanggan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="p-6">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- ============ MODAL EDIT PELANGGAN ============ --}}
    <div id="modal-edit" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Edit Pelanggan</h3>
                <button type="button" onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <form id="form-edit-customer" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" id="edit-name"
                        class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="phone" id="edit-phone"
                        class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit-email"
                        class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" id="edit-address" rows="3"
                        class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-700"></textarea>
                </div>

                <div class="flex gap-3 pt-2 justify-end">
                    <button type="button" onclick="closeModal('modal-edit')"
                        class="text-sm text-gray-600 px-5 py-2 rounded-md border hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-slate-800 text-white text-sm px-5 py-2 rounded-md hover:bg-slate-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ MODAL HAPUS PELANGGAN ============ --}}
    <div id="modal-delete" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Hapus Pelanggan</h3>
                <button type="button" onclick="closeModal('modal-delete')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus pelanggan <span id="delete-customer-name" class="font-semibold text-gray-800"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="form-delete-customer" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeModal('modal-delete')"
                        class="text-sm text-gray-600 px-5 py-2 rounded-md border hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-red-600 text-white text-sm px-5 py-2 rounded-md hover:bg-red-700">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, phone, email, address, actionUrl) {
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-address').value = address;
            document.getElementById('form-edit-customer').action = actionUrl;
            document.getElementById('modal-edit').classList.remove('hidden');
            document.getElementById('modal-edit').classList.add('flex');
        }

        function openDeleteModal(name, actionUrl) {
            document.getElementById('delete-customer-name').textContent = name;
            document.getElementById('form-delete-customer').action = actionUrl;
            document.getElementById('modal-delete').classList.remove('hidden');
            document.getElementById('modal-delete').classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }
    </script>

</x-dashboardlayout>