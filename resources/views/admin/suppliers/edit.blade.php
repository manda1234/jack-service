<x-dashboardlayout :title="'Edit Supplier'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    <div class="max-w-lg bg-white rounded-xl shadow p-6">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.suppliers.update', $supplier->id) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 text-sm">{{ old('address', $supplier->address) }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">Simpan Perubahan</button>
                <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 rounded-lg text-sm border">Batal</a>
            </div>
        </form>
    </div>

</x-dashboardlayout>