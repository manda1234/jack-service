<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jack Service Elektronik — Servis Elektronik Terpercaya di Bukittinggi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ===== Melayani Service — scroll reveal ===== */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* staggered delay via data attribute, set inline per item */

        /* gold checkmark pop */
        @keyframes popIn {
            0%   { transform: scale(0); opacity: 0; }
            70%  { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); }
        }
        .check-pop.is-visible .check-badge {
            animation: popIn .45s cubic-bezier(.34,1.56,.64,1) forwards;
        }

        /* soft floating badge, like the poster's "GARANSI SERVICE" seal */
        @keyframes floaty {
            0%, 100% { transform: translateY(0) rotate(-3deg); }
            50%      { transform: translateY(-6px) rotate(3deg); }
        }
        .badge-float { animation: floaty 3.2s ease-in-out infinite; }

        /* gold shimmer sweep for the section eyebrow, echoes the poster's foil text */
        @keyframes shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .text-shimmer {
            background: linear-gradient(90deg, #facc15 0%, #fde68a 25%, #facc15 50%, #eab308 75%, #facc15 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: shimmer 3.5s linear infinite;
        }

        /* device icon cards — hover lift, matches poster's device row */
        .device-card {
            transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
        }
        .device-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 30px -12px rgba(250, 204, 21, 0.25);
            border-color: #facc15;
        }
        .device-icon {
            transition: transform .35s ease;
        }
        .device-card:hover .device-icon {
            transform: rotate(-6deg) scale(1.1);
        }

        /* pulsing ring behind the guarantee badge */
        @keyframes ringPulse {
            0%   { box-shadow: 0 0 0 0 rgba(250, 204, 21, .45); }
            100% { box-shadow: 0 0 0 16px rgba(250, 204, 21, 0); }
        }
        .ring-pulse { animation: ringPulse 2s ease-out infinite; }

        @media (prefers-reduced-motion: reduce) {
            .reveal, .check-pop .check-badge, .badge-float, .text-shimmer, .device-card, .device-icon, .ring-pulse {
                animation: none !important;
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    {{-- NAVBAR --}}
    <header class="bg-slate-800 text-white">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div>
                <p class="font-bold text-lg leading-tight">Jack Service</p>
                <p class="text-xs text-slate-400 leading-tight">Elektronik — Bukittinggi</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('pelanggan.login') }}"
                   class="bg-white text-slate-800 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-100">
                    Login Pelanggan
                </a>
               
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <section class="bg-slate-800 text-white pb-20 pt-10 relative overflow-hidden">
        {{-- ambient glow, echoes the poster's blue/gold light --}}
        <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -right-24 w-96 h-96 bg-yellow-400/10 rounded-full blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center relative">
            <div>
                <span class="inline-block bg-yellow-400/10 border border-yellow-400/40 text-yellow-300 text-xs font-semibold px-3 py-1 rounded-full mb-4">
                    CEPAT • TERPERCAYA • BERGARANSI
                </span>
                <h1 class="text-4xl font-bold leading-tight mb-4">
                    Servis Elektronik Rumah Tangga,<br>
                    <span class="text-slate-300">Tanpa Perlu Nunggu Kabar.</span>
                </h1>
                <p class="text-slate-300 mb-8">
                    Ajukan servis TV, mesin cuci, kipas angin, pompa air, dan perangkat elektronik lainnya
                    langsung dari website. Pantau status perbaikan kapan saja, di mana saja.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('pelanggan.register') }}"
                       class="bg-white text-slate-800 font-medium px-6 py-3 rounded-lg hover:bg-gray-100">
                        Daftar &amp; Ajukan Servis
                    </a>
                    <a href="#melayani-service"
                       class="border border-slate-500 text-white px-6 py-3 rounded-lg hover:bg-slate-700">
                        Lihat Layanan
                    </a>
                </div>
            </div>
            <div class="relative">
                {{-- floating guarantee seal, like the poster's gold "GARANSI SERVICE" badge --}}
                <div class="badge-float absolute -top-5 -right-3 z-10 bg-yellow-400 text-slate-900 text-xs font-bold w-20 h-20 rounded-full flex items-center justify-center text-center leading-tight shadow-lg ring-pulse">
                    GARANSI<br>SERVICE
                </div>
                <div class="bg-slate-700 rounded-2xl p-8 text-center">
                    <p class="text-slate-300 text-sm mb-2">Contoh Status Servis Anda</p>
                    <div class="bg-slate-800 rounded-xl p-5 text-left space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Kode service</span>
                            <span class="font-mono">JSE-8X2K9F1A</span>
                        </div>
                        <div class="flex justify-between text-sm items-center">
                            <span class="text-slate-400">Status</span>
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">Sedang Diperiksa</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Barang</span>
                            <span>Mesin Cuci (LG)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JENIS LAYANAN --}}
    <section class="max-w-6xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-bold text-center mb-2">Dua Pilihan Layanan</h2>
        <p class="text-gray-500 text-center mb-10">Sesuaikan dengan kondisi Anda</p>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow p-8">
                <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72L4.318 3.44A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72" />
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2">Workshop</h3>
                <p class="text-gray-500 text-sm mb-4">
                    Bawa langsung barang elektronik Anda ke toko kami. Cocok untuk barang yang mudah dibawa
                    seperti kipas angin, set top box, atau perangkat kecil lainnya.
                </p>
                <p class="text-sm font-medium">📍 Jack Service Elektronik, Bukittinggi</p>
            </div>

            <div class="bg-white rounded-2xl shadow p-8">
                <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2">Home Service</h3>
                <p class="text-gray-500 text-sm mb-4">
                    Teknisi kami yang datang ke lokasi Anda. Cocok untuk barang besar seperti mesin cuci
                    atau pompa air yang sulit dibawa ke toko.
                </p>
                <p class="text-sm font-medium">🏠 Teknisi datang sesuai jadwal yang disepakati</p>
            </div>
        </div>
    </section>

    {{-- MELAYANI SERVICE — matches the poster's gold checklist + device row --}}
    <section id="melayani-service" class="bg-slate-800 text-white py-16 relative overflow-hidden">
        <div class="pointer-events-none absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-yellow-400/5 rounded-full blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-6 relative">
            <p class="text-shimmer text-center font-bold tracking-widest text-sm mb-2">MELAYANI SERVICE</p>
            <h2 class="text-2xl font-bold text-center mb-10">Semua Jenis Elektronik, Satu Tempat</h2>

            {{-- gold checklist --}}
            <div class="grid sm:grid-cols-2 gap-x-12 gap-y-4 max-w-3xl mx-auto mb-14">
                @php
                    $layanan = [
                        'TV LED / LCD', 'TV Tabung', 'Speaker Aktif', 'Tape Mobil', 'Amplifier',
                        'Mesin Cuci', 'Kulkas', 'Kipas Angin', 'Parabola', 'Dan Lain-lain',
                    ];
                @endphp
                @foreach ($layanan as $i => $item)
                    <div class="reveal check-pop flex items-center gap-3" style="transition-delay: {{ $i * 70 }}ms">
                        <span class="check-badge flex-shrink-0 w-7 h-7 rounded-full bg-yellow-400 text-slate-900 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="text-slate-200">{{ $item }}</span>
                    </div>
                @endforeach
            </div>

            {{-- device icon row, hover-animated like the poster's photo strip --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $devices = [
                        ['label' => 'TV LED', 'icon' => 'M3.75 3.75h16.5v12h-16.5v-12ZM8.25 20.25h7.5M12 15.75v4.5'],
                        ['label' => 'TV Tabung', 'icon' => 'M4.5 5.25h9v10.5h-9v-10.5ZM13.5 8.25l6-2.25v9l-6-2.25M4.5 18.75h9'],
                        ['label' => 'Mesin Cuci', 'icon' => 'M4.5 3.75h15v16.5h-15v-16.5ZM4.5 7.5h15M9.75 5.625h.008v.008h-.008v-.008ZM12 14.25a2.75 2.75 0 1 0 0-5.5 2.75 2.75 0 0 0 0 5.5Z'],
                        ['label' => 'Kulkas', 'icon' => 'M6 3.75h12v16.5H6V3.75ZM6 10.5h12M9 6.75h.008v.008H9V6.75ZM9 13.5h.008v.008H9V13.5Z'],
                        ['label' => 'Speaker Aktif', 'icon' => 'M6 4.5h12v15H6v-15ZM12 9a2.25 2.25 0 1 0 0 4.5A2.25 2.25 0 0 0 12 9ZM12 6.75h.008v.008H12V6.75Z'],
                    ];
                @endphp
                @foreach ($devices as $i => $d)
                    <div class="reveal device-card bg-slate-700 border border-slate-600 rounded-xl p-4 text-center" style="transition-delay: {{ $i * 90 }}ms">
                        <div class="device-icon w-12 h-12 mx-auto mb-2 rounded-lg bg-yellow-400/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#facc15" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d['icon'] }}" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-300">{{ $d['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CARA KERJA --}}
    <section id="cara-kerja" class="bg-white py-16 border-t">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-center mb-2">Cara Kerja</h2>
            <p class="text-gray-500 text-center mb-10">Empat langkah sederhana</p>

            <div class="grid md:grid-cols-4 gap-6">
                @php
                    $steps = [
                        ['no' => '1', 'title' => 'Daftar & Ajukan', 'desc' => 'Buat akun, isi form permintaan servis: jenis barang, keluhan, dan pilih Workshop atau Home Service.'],
                        ['no' => '2', 'title' => 'Diverifikasi Admin', 'desc' => 'Tim kami memverifikasi dan menugaskan teknisi yang sesuai.'],
                        ['no' => '3', 'title' => 'Dikerjakan Teknisi', 'desc' => 'Teknisi memeriksa, mendiagnosa, dan memperbaiki barang Anda.'],
                        ['no' => '4', 'title' => 'Pantau & Selesai', 'desc' => 'Pantau status kapan saja lewat dashboard, bayar setelah selesai.'],
                    ];
                @endphp
                @foreach ($steps as $step)
                    <div class="text-center">
                        <div class="w-10 h-10 bg-slate-800 text-white rounded-full flex items-center justify-center mx-auto mb-3 font-semibold">
                            {{ $step['no'] }}
                        </div>
                        <h4 class="font-semibold mb-1">{{ $step['title'] }}</h4>
                        <p class="text-sm text-gray-500">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-slate-800 text-white py-14">
        <div class="max-w-3xl mx-auto text-center px-6">
            <h2 class="text-2xl font-bold mb-3">Barang Elektronik Rusak?</h2>
            <p class="text-slate-300 mb-6">Daftar sekarang, ajukan servis dalam hitungan menit.</p>
            <a href="{{ route('pelanggan.register') }}"
               class="bg-white text-slate-800 font-medium px-8 py-3 rounded-lg hover:bg-gray-100 inline-block">
                Daftar Sekarang
            </a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 text-slate-400 text-sm py-8">
        <div class="max-w-6xl mx-auto px-6 flex justify-between items-center">
            <p>&copy; {{ date('Y') }} Jack Service Elektronik — Bukittinggi</p>
            <a href="{{ route('login') }}" class="hover:text-white">Login Admin/Teknisi</a>
        </div>
    </footer>

    <script>
        // Scroll-reveal for "Melayani Service" — matches the entrance feel of the poster
        document.addEventListener('DOMContentLoaded', function () {
            const items = document.querySelectorAll('#melayani-service .reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            items.forEach((item) => observer.observe(item));
        });
    </script>

</body>
</html>