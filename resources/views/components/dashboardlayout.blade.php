@include('partials.icons')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Jack Service Elektronik' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased"
      x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
    <div class="flex min-h-screen relative">

        {{-- OVERLAY untuk mobile --}}
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 md:hidden"
             style="display: none;"></div>

        {{-- SIDEBAR --}}
        <aside
            class="bg-slate-900 text-white flex-shrink-0 fixed md:static inset-y-0 left-0 z-40 transform transition-all duration-200 md:translate-x-0 flex flex-col border-r border-slate-800"
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
                sidebarCollapsed ? 'md:w-20' : 'md:w-64',
                'w-64'
            ]">

            <div class="px-6 py-5 border-b border-slate-800 flex justify-between items-center">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center text-slate-900 font-bold text-sm shrink-0">
                        JS
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity class="min-w-0">
                        <h1 class="text-base font-bold leading-tight truncate">Jack Service</h1>
                        <p class="text-[11px] text-amber-400 tracking-wide uppercase">Elektronik</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white transition shrink-0">
                    ✕
                </button>
            </div>

            <nav class="mt-4 px-3 space-y-1 flex-1 overflow-y-auto overflow-x-hidden
                        [&_a]:flex [&_a]:items-center [&_a]:gap-3 [&_a]:px-3 [&_a]:py-2.5 [&_a]:rounded-lg
                        [&_a]:text-sm [&_a]:text-slate-300 [&_a]:transition [&_a]:duration-150
                        [&_a:hover]:bg-slate-800 [&_a:hover]:text-white
                        [&_a.active]:bg-amber-500/10 [&_a.active]:text-amber-400 [&_a.active]:font-medium
                        [&_svg]:w-5 [&_svg]:h-5 [&_svg]:shrink-0 [&_svg]:opacity-80"
                :class="sidebarCollapsed ? 'md:[&_span]:hidden' : ''">
                @includeIf($sidebar ?? 'layouts.navigation-admin')
            </nav>

            <div class="px-4 py-4 border-t border-slate-800">
                <p x-show="!sidebarCollapsed" class="text-[11px] text-slate-500 truncate">&copy; {{ date('Y') }} Jack Service Elektronik</p>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col w-full md:ml-0 min-w-0">

            {{-- TOP BAR --}}
            <header class="bg-white/90 backdrop-blur border-b border-gray-200 px-4 md:px-6 py-4 flex justify-between items-center sticky top-0 z-20">
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Toggle mobile: buka/tutup drawer --}}
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-slate-900 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    {{-- Toggle desktop: collapse jadi icon-only --}}
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden md:flex text-gray-600 hover:text-slate-900 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <h2 class="text-lg md:text-xl font-semibold text-gray-800 truncate">{{ $title ?? 'Dashboard' }}</h2>
                        <p class="hidden md:block text-xs text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 md:gap-4 shrink-0">
                    <div class="hidden sm:flex items-center gap-2 pl-3 pr-1">
                        <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-semibold">
                            {{ strtoupper(substr($userName ?? 'U', 0, 1)) }}
                        </div>
                        <span class="text-sm text-gray-700 font-medium">{{ $userName ?? '' }}</span>
                    </div>

                    <form method="POST" action="{{ $logoutRoute ?? route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1.5 text-sm text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                            {!! sidebarIcon('logout', 'w-4 h-4') !!}
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 p-4 md:p-6 overflow-x-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
       @stack('scripts')
</body>
</html>