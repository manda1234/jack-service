<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function download(Service $service)
    {
        $isOwnerCustomer = Auth::guard('customer')->check()
            && Auth::guard('customer')->id() === $service->customer_id;
        $isAdmin = Auth::guard('web')->check() && Auth::user()->role === 'Admin';
        $isAssignedTechnician = Auth::guard('web')->check() && Auth::id() === $service->technician_id;

        abort_unless($isOwnerCustomer || $isAdmin || $isAssignedTechnician, 403);

        $service->load('customer', 'technician', 'spareparts.sparepart', 'payment');

        $pdf = Pdf::loadView('invoice.pdf', compact('service'));

        return $pdf->download('Invoice-' . $service->service_code . '.pdf');
    }
}