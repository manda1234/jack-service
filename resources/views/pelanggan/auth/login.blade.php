<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pelanggan — Jack Service Elektronik</title>
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
                    Pantau Status Servis Anda,<br>Kapan Saja.
                </h2>
                <p class="text-slate-300 text-sm">
                    Login untuk melihat perkembangan perbaikan barang elektronik Anda secara real-time,
                    tanpa perlu menghubungi kami untuk sekadar bertanya.
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

                <h1 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang Kembali</h1>
                <p class="text-gray-500 text-sm mb-6">Masuk untuk memantau status servis Anda</p>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm p-3 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('pelanggan.login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP atau Email</label>
                        <input type="text" name="identifier" value="{{ old('identifier') }}" required autofocus
                               placeholder="08xxxxxxxxxx atau email@contoh.com"
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="rounded border-gray-300">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-slate-800 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-slate-700">
                        Masuk
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('pelanggan.register') }}" class="text-slate-800 font-medium hover:underline">Daftar di sini</a>
                </p>

                <p class="mt-3 text-center text-xs text-gray-400">
                    <a href="{{ url('/') }}" class="hover:underline">&larr; Kembali ke beranda</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>