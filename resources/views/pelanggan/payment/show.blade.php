<x-dashboardlayout :title="'Pembayaran'" :userName="Auth::guard('customer')->user()->name" :logoutRoute="route('pelanggan.logout')" :sidebar="'layouts.navigation-pelanggan'">

    <div class="max-w-2xl mx-auto space-y-6" x-data="{ method: null }">

        {{-- Card: Info Servis --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm font-medium text-gray-500 mb-4">Informasi Servis</p>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Service Code</p>
                    <p class="font-semibold">{{ $service->service_code }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Nama Pelanggan</p>
                    <p class="font-semibold">{{ $service->customer->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Barang</p>
                    <p class="font-semibold">{{ $service->item_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Merk</p>
                    <p class="font-semibold">{{ $service->brand ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500">Keluhan</p>
                    <p class="font-semibold">{{ $service->complaint }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Rincian Tagihan --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm font-medium text-gray-500 mb-4">Rincian Tagihan</p>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Biaya Service</span>
                    <span class="font-medium">Rp {{ number_format($service->service_fee, 0, ',', '.') }}</span>
                </div>

                @if ($service->spareparts->where('approval_status', 'Disetujui')->isNotEmpty())
                    @foreach ($service->spareparts->where('approval_status', 'Disetujui') as $usage)
                        <div class="flex justify-between text-gray-500 pl-3">
                            <span>{{ $usage->sparepart->name }} x{{ $usage->quantity }}</span>
                            <span>Rp {{ number_format($usage->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                @endif

                <div class="flex justify-between border-t pt-2">
                    <span class="text-gray-600">Total Sparepart</span>
                    <span class="font-medium">Rp {{ number_format($service->sparepart_cost, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between border-t pt-3 mt-2">
                    <span class="font-semibold text-gray-800">Total Keseluruhan</span>
                    <span class="font-bold text-lg text-slate-800">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Card: Pilih Metode Pembayaran --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm font-medium text-gray-500 mb-4">Pilih Metode Pembayaran</p>

            <div class="grid grid-cols-2 gap-4">
                {{-- Opsi Tunai --}}
                <label
                    class="cursor-pointer rounded-xl border-2 p-4 flex flex-col items-center gap-2 transition"
                    :class="method === 'Tunai' ? 'border-slate-800 bg-slate-50' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="payment_method" value="Tunai" x-model="method" class="hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8" :class="method === 'Tunai' ? 'text-slate-800' : 'text-gray-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                    <span class="text-sm font-medium" :class="method === 'Tunai' ? 'text-slate-800' : 'text-gray-500'">Tunai</span>
                </label>

                {{-- Opsi QRIS --}}
                <label
                    class="cursor-pointer rounded-xl border-2 p-4 flex flex-col items-center gap-2 transition"
                    :class="method === 'QRIS' ? 'border-slate-800 bg-slate-50' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="payment_method" value="QRIS" x-model="method" class="hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8" :class="method === 'QRIS' ? 'text-slate-800' : 'text-gray-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h4.5v4.5h-4.5v-4.5Zm0 10.5h4.5v4.5h-4.5v-4.5Zm10.5-10.5h4.5v4.5h-4.5v-4.5Zm0 6.75h.008v.008h-.008v-.008Zm0 3.75h.008v.008h-.008v-.008Zm3.75-3.75h.008v.008h-.008v-.008Zm0 3.75h.008v.008h-.008v-.008Zm-3.75 3.75h.008v.008h-.008v-.008Zm3.75 0h.008v.008h-.008v-.008Z" />
                    </svg>
                    <span class="text-sm font-medium" :class="method === 'QRIS' ? 'text-slate-800' : 'text-gray-500'">QRIS</span>
                </label>
            </div>

            {{-- Placeholder detail per metode — akan diisi di Step 6 (Tunai) & Step 7 (QRIS) --}}
            <div class="mt-6">
               <div x-show="method === 'Tunai'" x-transition class="bg-gray-50 rounded-lg p-4 space-y-4">

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 text-sm p-3 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('pelanggan.services.payment.tunai', $service->id) }}"
          id="form-tunai"
          x-data="{ amountPaid: '', total: {{ $service->total_cost }} }"
          @submit="if (Number(amountPaid) < total) {
                $event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Nominal Kurang',
                    text: 'Nominal yang dibayar masih kurang dari total tagihan.',
                    confirmButtonColor: '#1e293b',
                });
            }">
        @csrf

        <div>
            <label class="block text-sm text-gray-700 mb-1">Total Tagihan</label>
            <p class="text-lg font-bold text-slate-800">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</p>
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Nominal Dibayar</label>
            <input type="number" name="amount_paid" x-model="amountPaid" min="0" required
                   placeholder="Masukkan nominal uang tunai"
                   class="w-full rounded-lg border-gray-300 text-sm">
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Kembalian</label>
            <p class="text-lg font-bold"
               :class="(Number(amountPaid) - total) >= 0 ? 'text-green-600' : 'text-red-500'"
               x-text="'Rp ' + Math.max(Number(amountPaid) - total, 0).toLocaleString('id-ID')">
                Rp 0
            </p>
        </div>

        <button type="submit" class="w-full bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700">
            Konfirmasi Pembayaran Tunai
        </button>
    </form>
</div>
               <div x-show="method === 'QRIS'" x-transition class="bg-gray-50 rounded-lg p-4 space-y-4 text-center">

    @if ($service->payment && $service->payment->payment_status === 'Menunggu Verifikasi')
        <div class="bg-yellow-50 text-yellow-700 text-sm p-3 rounded-lg">
            Pembayaran sudah dilaporkan, sedang menunggu verifikasi dari Admin.
        </div>
    @else
        <p class="text-sm font-medium text-gray-700">QRIS Jack Service Elektronik</p>

<img src="{{ asset('storage/images/qris-jack-service.jpeg') }}"
     class="mx-auto w-48 h-48 object-contain border border-gray-200 rounded-lg bg-white p-2">

<p class="text-lg font-bold text-slate-800">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</p>

<div class="bg-blue-50 text-blue-700 text-xs p-3 rounded-lg text-left">
    Silakan scan QRIS menggunakan aplikasi pembayaran Anda.<br>
    Setelah pembayaran berhasil, klik tombol "Saya Sudah Membayar" di bawah.
</div>

<form id="form-qris" method="POST" action="{{ route('pelanggan.services.payment.qris', $service->id) }}">
    @csrf
</form>
<button type="button"
        onclick="confirmAction('form-qris', {title: 'Konfirmasi Anda sudah menyelesaikan pembayaran QRIS?', icon: 'question', confirmColor: '#16a34a', confirmText: 'Ya, Saya Sudah Membayar'})"
        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
    Saya Sudah Membayar
</button>
@endif
            </div>
        </div>

    </div>

</x-dashboardlayout>