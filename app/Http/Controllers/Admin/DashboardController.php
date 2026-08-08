<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'menunggu_konfirmasi' => Service::where('status', 'Menunggu Konfirmasi Admin')->count(),
            'sedang_berjalan' => Service::whereNotIn('status', ['Selesai', 'Ditolak', 'Menunggu Konfirmasi Admin'])->count(),
            'selesai_bulan_ini' => Service::where('status', 'Selesai')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
            'pendapatan_bulan_ini' => Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
        ];

        $sparepartMenipis = Sparepart::where('stock', '<=', 5)->orderBy('stock')->get();

        // Data grafik pendapatan 6 bulan terakhir
        $pendapatanPerBulan = Payment::select(
                DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as bulan"),
                DB::raw('SUM(amount) as total')
            )
            ->where('payment_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i)->format('Y-m');
            $chartLabels[] = now()->subMonths($i)->translatedFormat('M Y');
            $found = $pendapatanPerBulan->firstWhere('bulan', $bulan);
            $chartData[] = $found ? (float) $found->total : 0;
        }

        $servicesTerbaru = Service::with('customer')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'sparepartMenipis', 'chartLabels', 'chartData', 'servicesTerbaru'));
    }
}