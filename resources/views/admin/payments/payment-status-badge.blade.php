@php

    $colors = [

        'Pending' => 'bg-gray-100 text-gray-700',

        'Menunggu Pembayaran' => 'bg-yellow-100 text-yellow-800',

        'Menunggu Verifikasi' => 'bg-orange-100 text-orange-700',

        'Lunas' => 'bg-green-100 text-green-700',

    ];

    $colorClass = $colors[$status] ?? 'bg-gray-100 text-gray-700';

@endphp

<span class="inline-block {{ $colorClass }} text-xs px-2 py-1 rounded-full font-medium">

    {{ $status }}

</span>