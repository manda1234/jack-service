@php
    $menu = [
        [
            'label' => 'Dashboard',
            'route' => 'teknisi.dashboard',
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z"/>
                        </svg>',
        ],
        [
            'label' => 'Workshop',
            'route' => 'teknisi.services.index',
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76Z"/>
                        </svg>',
        ],
        [
            'label' => 'Jadwal Home Service',
            'route' => 'teknisi.schedule.index',
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>',
        ],
    ];
@endphp

@foreach ($menu as $item)
    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
              {{ request()->routeIs($item['route']) ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
        {!! $item['icon'] !!}
        <span>{{ $item['label'] }}</span>
    </a>
@endforeach