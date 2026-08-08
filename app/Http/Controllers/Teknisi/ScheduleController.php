<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ScheduleController extends Controller
{
 public function index(Request $request)
{
    $query = Service::with('customer')
        ->where('technician_id', auth()->id())
        ->where('service_type', 'Home Service'); // <-- diganti, sesuai value asli di DB

    if ($request->filled('date')) {
        $query->where(function ($q) use ($request) {
            $q->whereDate('scheduled_visit_date', $request->date)
              ->orWhere(function ($q2) use ($request) {
                  $q2->whereNull('scheduled_visit_date')
                     ->whereDate('preferred_visit_date', $request->date);
              });
        });
    }

    $jadwal = $query->get()
        ->sortBy(function ($item) {
            $date = $item->scheduled_visit_date ?? $item->preferred_visit_date;
            $time = $item->scheduled_visit_time ?? $item->preferred_visit_time;
            return $date.' '.$time;
        });

    return view('teknisi.schedule.index', compact('jadwal'));
}
}