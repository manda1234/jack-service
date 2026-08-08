<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use Illuminate\Http\Request; 
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
{
    $query = Service::where('customer_id', Auth::guard('customer')->id());

    if ($request->filled('service_type')) {
        $query->where('service_type', $request->service_type);
    }

    $services = $query->latest()->paginate(10);

    return view('pelanggan.services.index', compact('services'));
}

    public function create()
    {
        return view('pelanggan.services.create');
    }

public function store(StoreServiceRequest $request)
{
    $validated = $request->validated();

    $photoPath = null;
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('services', 'public');
    }

    $service = Service::create([
        'customer_id' => Auth::guard('customer')->id(),
        'service_code' => 'JSE-' . strtoupper(Str::random(8)),
        'service_type' => $validated['service_type'],
        'item_name' => $validated['item_name'],
        'brand' => $validated['brand'] ?? null,
        'complaint' => $validated['complaint'],
        'photo' => $photoPath,
        'address' => $validated['address'] ?? null,
        'latitude' => $validated['latitude'] ?? null,
        'longitude' => $validated['longitude'] ?? null,
        'preferred_visit_date' => $validated['preferred_visit_date'] ?? null,
        'preferred_visit_time' => $validated['preferred_visit_time'] ?? null,
        'notes' => $validated['notes'] ?? null,
        'status' => 'Menunggu Konfirmasi Admin',
    ]);

    return redirect()
        ->route('pelanggan.services.show', $service->id)
        ->with('success', 'Permintaan servis berhasil dikirim. Kode service: ' . $service->service_code);
}
  public function show(Service $service)
{
    // pastikan hanya pemilik servis yang bisa akses
    abort_unless($service->customer_id === Auth::guard('customer')->id(), 403);

    $service->load('spareparts.sparepart');

    if (request()->ajax()) {
        return view('pelanggan.services.detail-partial', compact('service'));
    }

    return view('pelanggan.services.detail', compact('service'));
}
}