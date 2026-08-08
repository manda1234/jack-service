<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\ServiceSparepart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SparepartApprovalController extends Controller
{
    public function approve(ServiceSparepart $serviceSparepart)
    {
        $service = $serviceSparepart->service;
        abort_if($service->customer_id !== Auth::guard('customer')->id(), 403);
        abort_if($serviceSparepart->approval_status !== 'Menunggu Persetujuan', 400, 'Sudah diproses sebelumnya.');

        $sparepart = $serviceSparepart->sparepart;

        if ($sparepart->stock < $serviceSparepart->quantity) {
            return back()->with('error', 'Stok sparepart sudah tidak mencukupi, hubungi Admin.');
        }

        DB::transaction(function () use ($serviceSparepart, $sparepart, $service) {
            $serviceSparepart->update(['approval_status' => 'Disetujui']);
            $sparepart->reduceStock($serviceSparepart->quantity);

            $newSparepartCost = $service->spareparts()->where('approval_status', 'Disetujui')->sum('subtotal');
            $service->update([
                'sparepart_cost' => $newSparepartCost,
                'total_cost' => $service->service_fee + $newSparepartCost,
            ]);
        });

        return back()->with('success', 'Penggantian sparepart disetujui.');
    }

    public function reject(ServiceSparepart $serviceSparepart)
    {
        $service = $serviceSparepart->service;
        abort_if($service->customer_id !== Auth::guard('customer')->id(), 403);
        abort_if($serviceSparepart->approval_status !== 'Menunggu Persetujuan', 400, 'Sudah diproses sebelumnya.');

        $serviceSparepart->update(['approval_status' => 'Ditolak']);

        return back()->with('success', 'Penggantian sparepart ditolak. Teknisi akan mencari solusi lain.');
    }
}