<x-dashboardlayout :title="'Edit Teknisi'" :userName="Auth::user()->name" :sidebar="'layouts.navigation-admin'">

    <div class="max-w-lg bg-white rounded-xl shadow p-6">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.technicians.update', $technician->id) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $technician->name) }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $technician->email) }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                </label>
                <input type="password" name="password" minlength="8" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">Simpan Perubahan</button>
                <a href="{{ route('admin.technicians.index') }}" class="px-4 py-2 rounded-lg text-sm border">Batal</a>
            </div>
        </form>
    </div>

</x-dashboardlayout>