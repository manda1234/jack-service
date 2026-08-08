<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $technicianId = Auth::id();

        $stats = [
            'antrian' => Service::where('technician_id', $technicianId)
                ->whereIn('status', ['Menunggu Diperiksa Teknisi'])
                ->count(),
            'sedang_dikerjakan' => Service::where('technician_id', $technicianId)
                ->whereIn('status', ['Sedang Diperiksa', 'Menunggu Sparepart'])
                ->count(),
            'selesai_bulan_ini' => Service::where('technician_id', $technicianId)
                ->whereIn('status', ['Selesai Diperbaiki', 'Selesai'])
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
        ];

        $tugasPrioritas = Service::where('technician_id', $technicianId)
            ->whereNotIn('status', ['Selesai', 'Ditolak'])
            ->with('customer')
            ->orderBy('created_at')
            ->take(5)
            ->get();

        return view('teknisi.dashboard', compact('stats', 'tugasPrioritas'));
    }
}