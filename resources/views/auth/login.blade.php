<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Staff — Jack Service Elektronik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="min-h-screen grid md:grid-cols-2">

        {{-- SISI KIRI: Branding --}}
        <div class="hidden md:flex bg-slate-800 text-white flex-col justify-between p-12">
            <a href="{{ url('/') }}">
                <p class="font-bold text-xl">Jack Service</p>
                <p class="text-xs text-slate-400">Elektronik — Bukittinggi</p>
            </a>

            <div>
                <h2 class="text-3xl font-bold leading-tight mb-4">
                    Portal Internal<br>Admin &amp; Teknisi
                </h2>
                <p class="text-slate-300 text-sm">
                    Kelola permintaan servis, penugasan, diagnosa, dan pembayaran dalam satu sistem terintegrasi.
                </p>
            </div>

            <p class="text-slate-500 text-xs">&copy; {{ date('Y') }} Jack Service Elektronik</p>
        </div>

        {{-- SISI KANAN: Form --}}
        <div class="flex items-center justify-center p-8">
            <div class="w-full max-w-sm">

                <div class="md:hidden mb-8 text-center">
                    <p class="font-bold text-xl text-slate-800">Jack Service Elektronik</p>
                </div>

                <h1 class="text-2xl font-bold text-gray-800 mb-1">Login Staff</h1>
                <p class="text-gray-500 text-sm mb-6">Khusus Admin &amp; Teknisi</p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm p-3 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="rounded border-gray-300">
                            Ingat saya
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-slate-600 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full bg-slate-800 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-slate-700">
                        Masuk
                    </button>
                </form>

                <p class="mt-6 text-center text-xs text-gray-400">
                    <a href="{{ url('/') }}" class="hover:underline">&larr; Kembali ke beranda</a>
                    &nbsp;|&nbsp;
                    <a href="{{ route('pelanggan.login') }}" class="hover:underline">Login sebagai Pelanggan</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>