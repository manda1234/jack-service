<x-dashboardlayout title="Edit Pelanggan">

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">

        <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}"
                    class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-700 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                    class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-700 @error('phone') border-red-500 @enderror">
                @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                    class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-700 @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="3"
                    class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-700 @error('address') border-red-500 @enderror">{{ old('address', $customer->address) }}</textarea>
                @error('address') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-slate-800 text-white text-sm px-5 py-2 rounded-md hover:bg-slate-700">
                    Update
                </button>
                <a href="{{ route('admin.customers.index') }}"
                   class="text-sm text-gray-600 px-5 py-2 rounded-md border hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-dashboardlayout>