<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
public function index(Request $request)
{
    $services = Service::with('customer', 'technician')
        ->when($request->filled('service_type'), function ($query) use ($request) {
            $query->where('service_type', $request->service_type);
        })
        ->latest()
        ->paginate(10);

    return view('admin.services.index', compact('services'));
}

    public function show(Service $service)
    {
        $service->load('customer', 'technician', 'payment', 'spareparts.sparepart');
        $technicians = User::where('role', 'Teknisi')->get();

        return view('admin.services.show', compact('service', 'technicians'));
    }

    public function reject(Request $request, Service $service)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $service->update([
            'status' => 'Ditolak',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Permintaan servis telah ditolak.');
    }

    public function assign(Service $service)
    {
        $technician = User::where('role', 'Teknisi')->first();

        abort_if(!$technician, 400, 'Belum ada data teknisi. Tambahkan dulu di menu Data Teknisi.');

        $newStatus = $service->service_type === 'Workshop'
            ? 'Menunggu Barang Diantar'
            : 'Menunggu Kunjungan Teknisi';

        $service->update([
            'technician_id' => $technician->id,
            'status' => $newStatus,
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Servis berhasil diteruskan ke teknisi.');
    }

    public function receiveItem(Service $service)
    {
        // Khusus Workshop: admin konfirmasi barang fisik sudah diterima
        abort_if($service->service_type !== 'Workshop', 400, 'Hanya untuk layanan Workshop.');

        $service->update(['status' => 'Menunggu Diperiksa Teknisi']);

        return redirect()
            ->route('admin.services.show', $service->id)
            ->with('success', 'Barang telah dikonfirmasi diterima.');
    }

    public function setFinalCost(Request $request, Service $service)
{
    abort_if($service->status !== 'Selesai Diperbaiki', 400, 'Servis belum selesai diperbaiki oleh teknisi.');

    $validated = $request->validate([
        'service_fee' => ['required', 'numeric', 'min:0'],
        'sparepart_cost' => ['required', 'numeric', 'min:0'],
    ]);

    $totalCost = $validated['service_fee'] + $validated['sparepart_cost'];

    $service->update([
        'service_fee' => $validated['service_fee'],
        'sparepart_cost' => $validated['sparepart_cost'],
        'total_cost' => $totalCost,
        'status' => 'Menunggu Pembayaran',
    ]);

    return redirect()
        ->route('admin.services.show', $service->id)
        ->with('success', 'Biaya telah ditentukan. Status diubah menjadi Menunggu Pembayaran.');
}
}