<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $customerId = Auth::guard('customer')->id();

        $services = \App\Models\Service::where('customer_id', $customerId)
            ->latest()
            ->get();

        $activeService = $services->whereNotIn('status', ['Selesai', 'Ditolak'])->first();

        $stats = [
            'total' => $services->count(),
            'berjalan' => $services->whereNotIn('status', ['Selesai', 'Ditolak'])->count(),
            'selesai' => $services->where('status', 'Selesai')->count(),
        ];

        $recentServices = $services->take(3);

        return view('pelanggan.dashboard', compact('activeService', 'stats', 'recentServices'));
    }
}