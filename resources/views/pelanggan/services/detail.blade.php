<x-dashboardlayout :title="'Detail Servis'" :userName="Auth::guard('customer')->user()->name" :logoutRoute="route('pelanggan.logout')" :sidebar="'layouts.navigation-pelanggan'">

    <div class="max-w-2xl bg-white rounded-xl shadow p-6">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Detail Permintaan Servis</h2>
            <a href="{{ route('pelanggan.services.index') }}" class="text-sm text-slate-600 hover:underline">
                &larr; Kembali ke Riwayat Servis
            </a>
        </div>

        @include('pelanggan.services.detail-partial', ['service' => $service])

    </div>

</x-dashboardlayout>

{{-- SweetAlert2 dibutuhkan oleh confirmAction() di detail-partial (tombol Setujui/Tolak sparepart) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAction(formId, opts) {
        Swal.fire({
            title: opts.title,
            icon: opts.icon || 'question',
            showCancelButton: true,
            confirmButtonText: opts.confirmText || 'Ya',
            cancelButtonText: 'Batal',
            confirmButtonColor: opts.confirmColor || '#1e293b',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>