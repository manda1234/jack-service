<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun — Jack Service Elektronik</title>
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
                    Satu Akun,<br>Semua Riwayat Servis Anda.
                </h2>
                <p class="text-slate-300 text-sm mb-6">
                    Daftar sekali, ajukan servis kapan saja — baik Workshop maupun Home Service — tanpa perlu
                    mengulang data dari awal.
                </p>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-2 text-slate-300">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                        Ajukan servis langsung dari website
                    </div>
                    <div class="flex items-center gap-2 text-slate-300">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                        Pantau status perbaikan secara real-time
                    </div>
                    <div class="flex items-center gap-2 text-slate-300">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                        Riwayat servis tersimpan otomatis
                    </div>
                </div>
            </div>

            <p class="text-slate-500 text-xs">&copy; {{ date('Y') }} Jack Service Elektronik</p>
        </div>

        {{-- SISI KANAN: Form --}}
        <div class="flex items-center justify-center p-8 py-12">
            <div class="w-full max-w-sm">

                <div class="md:hidden mb-8 text-center">
                    <p class="font-bold text-xl text-slate-800">Jack Service Elektronik</p>
                </div>

                <h1 class="text-2xl font-bold text-gray-800 mb-1">Buat Akun Baru</h1>
                <p class="text-gray-500 text-sm mb-6">Isi data di bawah untuk mulai menggunakan layanan kami</p>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm p-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('pelanggan.register.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                               placeholder="08xxxxxxxxxx"
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>

                    <button type="submit"
                            class="w-full bg-slate-800 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-slate-700">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('pelanggan.login') }}" class="text-slate-800 font-medium hover:underline">Masuk di sini</a>
                </p>

                <p class="mt-3 text-center text-xs text-gray-400">
                    <a href="{{ url('/') }}" class="hover:underline">&larr; Kembali ke beranda</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>