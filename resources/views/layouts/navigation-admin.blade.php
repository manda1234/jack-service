@php
    $menu = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard'],
        ['label' => 'Verifikasi Servis', 'route' => 'admin.services.index', 'icon' => 'check-circle'],
        ['label' => 'Data Teknisi', 'route' => 'admin.technicians.index', 'icon' => 'wrench'],
        ['label'=> 'Data Pelanggan' , 'route' => 'admin.customers.index' ,'icon' =>'user'],
        ['label' => 'Data Sparepart', 'route' => 'admin.spareparts.index', 'icon' => 'cube'],
        ['label' => 'Data Supplier', 'route' => 'admin.suppliers.index', 'icon' => 'truck'],
        ['label' => 'Pembayaran', 'route' => 'admin.payments.index', 'icon' => 'credit-card'],
        ['label' => 'Laporan', 'route' => 'admin.reports.index', 'icon' => 'chart-pie'],
        
    ];
@endphp

@foreach ($menu as $item)
    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
              {{ request()->routeIs($item['route']) ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
        {!! sidebarIcon($item['icon']) !!}
        <span>{{ $item['label'] }}</span>
    </a>
@endforeach