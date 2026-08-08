<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
public function index(Request $request)
{
    $query = Service::with('customer')
        ->where('technician_id', auth()->id())
        ->where('service_type', 'Workshop'); // hardcode, hanya Workshop

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('service_code', 'like', "%{$search}%")
              ->orWhere('item_name', 'like', "%{$search}%")
              ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%"));
        });
    }

    $services = $query->latest()->paginate(10);

    return view('teknisi.services.index', compact('services'));
}

    public function show(Service $service)
    {
        abort_if($service->technician_id !== Auth::id(), 403);

        $service->load('customer', 'spareparts.sparepart');
        $spareparts = Sparepart::where('stock', '>', 0)->get();

        return view('teknisi.services.show', compact('service', 'spareparts'));
    }

    // Konfirmasi mulai diperiksa (dari "Menunggu Diperiksa Teknisi" / "Barang Diterima" / teknisi sampai lokasi Home Service)
    public function startCheck(Service $service)
    {
        abort_if($service->technician_id !== Auth::id(), 403);

        $service->update(['status' => 'Sedang Diperiksa']);

        return back()->with('success', 'Status diperbarui: Sedang Diperiksa.');
    }

    public function saveDiagnosis(Request $request, Service $service)
    {
        abort_if($service->technician_id !== Auth::id(), 403);

        $validated = $request->validate([
            'diagnosis' => ['required', 'string'],
            'repair_action' => ['nullable', 'string'],
            'service_fee' => ['required', 'numeric', 'min:0'],
        ]);

        $service->update($validated);

        return back()->with('success', 'Diagnosa berhasil disimpan.');
    }

public function addSparepart(Request $request, Service $service)
{
    abort_if($service->technician_id !== Auth::id(), 403);

    $replacementType = $request->input('replacement_type', 'sparepart');

    // ================= Komponen Kecil =================
    if ($replacementType === 'component') {
        $validated = $request->validate([
            'replacement_type'      => ['required', 'in:component'],
            'components'            => ['required', 'array', 'min:1'],
            'components.*.name'     => ['required', 'string', 'max:255'],
            'components.*.quantity' => ['required', 'integer', 'min:1'],
            'components.*.note'     => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($validated['components'] as $component) {
            $service->spareparts()->create([
                'replacement_type'    => 'component',
                'sparepart_id'        => null,
                'component_name'      => $component['name'],
                'quantity'            => $component['quantity'],
                'subtotal'            => 0,
                'reason'              => $component['note'] ?? null,
                'approval_status'     => 'Termasuk Jasa',
                'included_in_service' => true,
            ]);
        }

        return back()->with('success', 'Komponen kecil berhasil dicatat (termasuk biaya jasa).');
    }

    // ================= Sparepart Utama (bisa lebih dari 1) =================
    $validated = $request->validate([
        'spareparts'                 => ['required', 'array', 'min:1'],
        'spareparts.*.sparepart_id'  => ['required', 'exists:spareparts,id'],
        'spareparts.*.quantity'      => ['required', 'integer', 'min:1'],
        'spareparts.*.reason'        => ['required', 'string', 'max:500'],
    ]);

    // Gabungkan qty jika sparepart yang sama dipilih di lebih dari satu baris
    $grouped = collect($validated['spareparts'])
        ->groupBy('sparepart_id')
        ->map(function ($items) {
            return [
                'quantity' => $items->sum('quantity'),
                'reason'   => $items->pluck('reason')->unique()->implode('; '),
            ];
        });

    $spareparts = Sparepart::whereIn('id', $grouped->keys())
        ->lockForUpdate()
        ->get()
        ->keyBy('id');

    // Cek stok semua item dulu sebelum insert apa pun
    $insufficient = [];
    foreach ($grouped as $sparepartId => $data) {
        $sp = $spareparts->get($sparepartId);
        if (!$sp || $sp->stock < $data['quantity']) {
            $insufficient[] = $sp->name ?? "ID $sparepartId";
        }
    }

    if (!empty($insufficient)) {
        $service->update(['status' => 'Menunggu Sparepart']);
        return back()->with(
            'error',
            'Stok tidak cukup untuk: ' . implode(', ', $insufficient) . '. Status diubah ke Menunggu Sparepart.'
        );
    }

    DB::transaction(function () use ($service, $grouped, $spareparts) {
        foreach ($grouped as $sparepartId => $data) {
            $sp = $spareparts[$sparepartId];

            $service->spareparts()->create([
                'replacement_type'    => 'sparepart',
                'sparepart_id'        => $sp->id,
                'component_name'      => null,
                'quantity'            => $data['quantity'],
                'subtotal'            => $sp->selling_price * $data['quantity'],
                'reason'              => $data['reason'],
                'approval_status'     => 'Menunggu Persetujuan',
                'included_in_service' => false,
            ]);
        }
    });

    return back()->with('success', 'Permintaan penggantian sparepart terkirim, menunggu persetujuan pelanggan.');
}
 public function complete(Service $service)
{
    abort_if($service->technician_id !== Auth::id(), 403);

    if ($service->spareparts()->where('approval_status', 'Menunggu Persetujuan')->exists()) {
        return back()->with('error', 'Masih ada permintaan sparepart yang menunggu persetujuan pelanggan.');
    }

    $service->update([
        'status' => 'Selesai Diperbaiki',
        'total_cost' => $service->service_fee + $service->sparepart_cost,
    ]);

    return redirect()->route('teknisi.services.index')->with('success', 'Servis ditandai selesai diperbaiki.');
}

    public function arriveAtLocation(Service $service)
{
    abort_if($service->technician_id !== Auth::id(), 403);
    abort_if($service->service_type !== 'Home Service', 400, 'Aksi ini khusus Home Service.');
    abort_if($service->status !== 'Menunggu Kunjungan Teknisi', 400, 'Status servis tidak sesuai.');

    $service->update(['status' => 'Sedang Diperiksa']);

    return back()->with('success', 'Status diperbarui: Teknisi sudah sampai, sedang memeriksa barang.');
}

public function storePayment(Service $service)
{
    if ($service->payment) {
        return back()->with('error', 'Pembayaran untuk service ini sudah tercatat.');
    }

    $service->payment()->create([
        'payment_date'   => now(),
        'amount'         => $service->total_cost,
        'payment_status' => 'Paid',
        'paid_by'        => 'Teknisi',
    ]);

    return redirect()
        ->route('teknisi.services.show', $service->id)
        ->with('success', 'Pembayaran tunai berhasil dikonfirmasi.');
}

public function confirmSchedule(Request $request, Service $service)
{
    abort_if($service->technician_id !== Auth::id(), 403);
    abort_if($service->service_type !== 'Home Service', 400, 'Hanya untuk layanan Home Service.');

    $validated = $request->validate([
        'scheduled_visit_date' => ['required', 'date', 'after_or_equal:today'],
        'scheduled_visit_time' => ['required', 'in:Pagi (08.00-11.00),Siang (11.00-14.00),Sore (14.00-17.00)'],
    ]);

    $service->update($validated);

    return back()->with('success', 'Jadwal kunjungan berhasil dikonfirmasi.');
}

}