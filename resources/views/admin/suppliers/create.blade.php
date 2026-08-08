<x-dashboardlayout :title="'Tambah Supplier'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    <div class="max-w-lg bg-white rounded-xl shadow p-6">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.suppliers.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 text-sm">{{ old('address') }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">Simpan</button>
                <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 rounded-lg text-sm border">Batal</a>
            </div>
        </form>
    </div>

</x-dashboardlayout>