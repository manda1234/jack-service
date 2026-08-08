<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Service $service)
    {
        abort_if($service->customer_id !== Auth::guard('customer')->id(), 403);
        abort_if($service->status !== 'Menunggu Pembayaran', 400, 'Servis ini belum siap untuk dibayar.');

        $service->load('spareparts.sparepart');

        return view('pelanggan.payment.show', compact('service'));
    }

    public function storeTunai(Request $request, Service $service)
{
    abort_if($service->customer_id !== Auth::guard('customer')->id(), 403);
    abort_if($service->status !== 'Menunggu Pembayaran', 400, 'Servis ini belum siap untuk dibayar.');

    $validated = $request->validate([
        'amount_paid' => ['required', 'numeric', 'min:0'],
    ]);

    if ($validated['amount_paid'] < $service->total_cost) {
        return back()->withErrors(['amount_paid' => 'Nominal dibayar kurang dari total tagihan.'])->withInput();
    }

    $changeAmount = $validated['amount_paid'] - $service->total_cost;

    $service->payment()->create([
        'amount' => $service->total_cost,
        'payment_method' => 'Tunai',
        'amount_paid' => $validated['amount_paid'],
        'change_amount' => $changeAmount,
        'paid_by' => 'Pelanggan',
        'payment_status' => 'Lunas',
        'payment_date' => now(),
    ]);

    $service->update(['status' => 'Selesai']);

    return redirect()
        ->route('pelanggan.services.show', $service->id)
        ->with('success', 'Pembayaran tunai berhasil dicatat. Servis selesai.');
}
public function storeQris(Service $service)
{
    abort_if($service->customer_id !== Auth::guard('customer')->id(), 403);
    abort_if($service->status !== 'Menunggu Pembayaran', 400, 'Servis ini belum siap untuk dibayar.');

    // Cegah klik dobel kalau sudah pernah lapor bayar sebelumnya
    if ($service->payment) {
        return back()->with('error', 'Pembayaran sudah pernah dilaporkan, menunggu verifikasi Admin.');
    }

    $service->payment()->create([
        'amount' => $service->total_cost,
        'payment_method' => 'QRIS',
        'amount_paid' => $service->total_cost,
        'change_amount' => 0,
        'paid_by' => 'Pelanggan',
        'payment_status' => 'Menunggu Verifikasi',
        'payment_date' => now(),
    ]);

    return redirect()
        ->route('pelanggan.services.show', $service->id)
        ->with('success', 'Terima kasih. Pembayaran Anda sedang menunggu verifikasi dari Admin.');
}
}