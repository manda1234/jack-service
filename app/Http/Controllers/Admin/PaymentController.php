<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
  public function index(Request $request)
{
    $pendingVerification = Payment::with('service.customer')
        ->where('payment_status', 'Menunggu Verifikasi')
        ->latest()
        ->get();

    $payments = Payment::with('service.customer')
        ->when($request->filled('paid_by'), fn ($q) => $q->where('paid_by', $request->paid_by))
        ->when($request->filled('start_date'), fn ($q) => $q->whereDate('payment_date', '>=', $request->start_date))
        ->when($request->filled('end_date'), fn ($q) => $q->whereDate('payment_date', '<=', $request->end_date))
        ->latest('payment_date')
        ->paginate(15)
        ->withQueryString();

    $totalPendapatan = Payment::when($request->filled('paid_by'), fn ($q) => $q->where('paid_by', $request->paid_by))
        ->when($request->filled('start_date'), fn ($q) => $q->whereDate('payment_date', '>=', $request->start_date))
        ->when($request->filled('end_date'), fn ($q) => $q->whereDate('payment_date', '<=', $request->end_date))
        ->sum('amount');

    return view('admin.payments.index', compact('pendingVerification', 'payments', 'totalPendapatan'));
}

    public function store(Request $request, Service $service)
    {
        abort_if($service->service_type !== 'Workshop', 400, 'Pembayaran ini khusus layanan Workshop.');
        abort_if($service->status !== 'Selesai Diperbaiki', 400, 'Servis belum selesai diperbaiki.');

        $service->payment()->create([
            'amount' => $service->total_cost,
            'paid_by' => 'Admin',
            'payment_status' => 'Paid',
            'payment_date' => now(),
        ]);

        $service->update(['status' => 'Selesai']);

        return redirect()
            ->route('admin.services.show', $service->id)
            ->with('success', 'Pembayaran berhasil dicatat. Servis selesai.');
    }

    public function verify(Payment $payment)
{
    abort_if($payment->payment_status !== 'Menunggu Verifikasi', 400, 'Pembayaran ini tidak dalam status menunggu verifikasi.');

    $payment->update(['payment_status' => 'Lunas']);
    $payment->service->update(['status' => 'Selesai']);

    return redirect()
        ->route('admin.payments.index')
        ->with('success', 'Pembayaran berhasil diverifikasi. Servis selesai.');
}
}