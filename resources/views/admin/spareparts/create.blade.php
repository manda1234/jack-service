<x-dashboardlayout :title="'Tambah Sparepart'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    <div class="max-w-lg bg-white rounded-xl shadow p-6">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

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
                    <p class="text-xs text-gray-400 mt-1">Belum ada data supplier. Bisa ditambah nanti di menu Data Supplier.</p>
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
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">Simpan</button>
                <a href="{{ route('admin.spareparts.index') }}" class="px-4 py-2 rounded-lg text-sm border">Batal</a>
            </div>
        </form>
    </div>

</x-dashboardlayout>