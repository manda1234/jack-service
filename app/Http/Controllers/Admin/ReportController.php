<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Payment;
use App\Exports\ServiceReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected function getFilteredData(Request $request)
    {
        $start = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $end = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $services = Service::with('customer', 'technician', 'payment')
            ->whereBetween('created_at', [$start, $end . ' 23:59:59'])
            ->latest()
            ->get();

        $totalPendapatan = Payment::whereBetween('payment_date', [$start, $end . ' 23:59:59'])->sum('amount');

        return compact('services', 'totalPendapatan', 'start', 'end');
    }

    public function index(Request $request)
    {
        $data = $this->getFilteredData($request);

        $summary = [
            'total_servis' => $data['services']->count(),
            'selesai' => $data['services']->where('status', 'Selesai')->count(),
            'ditolak' => $data['services']->where('status', 'Ditolak')->count(),
            'berjalan' => $data['services']->whereNotIn('status', ['Selesai', 'Ditolak'])->count(),
            'total_pendapatan' => $data['totalPendapatan'],
        ];

        return view('admin.reports.index', array_merge($data, compact('summary')));
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getFilteredData($request);

        $pdf = Pdf::loadView('admin.reports.pdf', $data);

        return $pdf->download('Laporan-Servis-' . $data['start'] . '-sd-' . $data['end'] . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredData($request);

        return Excel::download(
            new ServiceReportExport($data['services']),
            'Laporan-Servis-' . $data['start'] . '-sd-' . $data['end'] . '.xlsx'
        );
    }
}