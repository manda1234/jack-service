<x-dashboardlayout :title="'Ajukan Servis'" :userName="Auth::guard('customer')->user()->name" :logoutRoute="route('pelanggan.logout')" :sidebar="'layouts.navigation-pelanggan'">
{{-- Card Informasi Area Layanan Home Service --}}

    <div class="max-w-2xl bg-white rounded-xl shadow p-6" x-data="{ serviceType: '{{ old('service_type', 'Workshop') }}' }">

        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pelanggan.services.store') }}" class="space-y-5" enctype="multipart/form-data">
            @csrf

            {{-- Info Pelanggan (read-only, dari akun yang login) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nama Customer</label>
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::guard('customer')->user()->name }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nomor HP</label>
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::guard('customer')->user()->phone }}</p>
                </div>
            </div>

            {{-- Jenis Layanan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Layanan</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer has-[:checked]:border-slate-800 has-[:checked]:bg-slate-50">
                        <input type="radio" name="service_type" value="Workshop" x-model="serviceType"
                               class="text-slate-800 focus:ring-slate-500"
                               {{ old('service_type', 'Workshop') === 'Workshop' ? 'checked' : '' }}>
                        <span class="text-sm">Workshop (datang ke toko)</span>
                    </label>
                    <label class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer has-[:checked]:border-slate-800 has-[:checked]:bg-slate-50">
                        <input type="radio" name="service_type" value="Home Service" x-model="serviceType"
                               class="text-slate-800 focus:ring-slate-500"
                               {{ old('service_type') === 'Home Service' ? 'checked' : '' }}>
                        <span class="text-sm">Home Service (teknisi ke lokasi)</span>
                    </label>
                </div>
            </div>

            {{-- Barang Elektronik --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang Elektronik</label>
                <input type="text" name="item_name" value="{{ old('item_name') }}" required
                       placeholder="Contoh: TV, Mesin Cuci, Kipas Angin"
                       class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500">
            </div>

            {{-- Merk --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Merek (opsional)</label>
                <input type="text" name="brand" value="{{ old('brand') }}"
                       class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500">
            </div>

            {{-- Keluhan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan Kerusakan</label>
                <textarea name="complaint" rows="4" required
                          class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500">{{ old('complaint') }}</textarea>
            </div>

            {{-- Foto Bukti Kerusakan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Foto Bukti Kerusakan
                </label>
                <input type="file" name="photo" id="photo-input" accept="image/png, image/jpeg, image/jpg"
                       class="w-full text-sm rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500
                              file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                              file:bg-slate-800 file:text-white file:text-sm hover:file:bg-slate-700">
                <p class="text-xs text-gray-400 mt-1">Format JPG/PNG, maksimal 2MB. Membantu teknisi memperkirakan kerusakan lebih awal.</p>

                <img id="photo-preview" src="#" alt="Preview foto"
                     class="mt-2 rounded-lg border border-gray-200 max-h-48 hidden">
            </div>

            {{-- ================= KHUSUS HOME SERVICE ================= --}}
            <div x-show="serviceType === 'Home Service'" x-transition
     x-effect="serviceType === 'Home Service' && window.dispatchEvent(new Event('home-service-map-visible'))"
     class="space-y-5 border-t border-gray-200 pt-5">

                {{-- Wilayah Home Service --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah Home Service</label>
                    <select name="region" id="region-select"
                            class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach (\App\Http\Requests\StoreServiceRequest::VALID_REGIONS as $region)
                            <option value="{{ $region }}" {{ old('region') === $region ? 'selected' : '' }}>{{ $region }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Bisa terisi otomatis kalau lokasi di peta cocok dengan salah satu wilayah di atas — tetap bisa diganti manual.</p>
                </div>

                {{-- Alamat Lengkap (field utama, otomatis terisi dari peta) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="address" id="address-textarea" rows="3"
                              placeholder="Jl. Sudirman No.12, dekat Masjid Raya, rumah cat hijau."
                              class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">{{ old('address') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Cari lokasi atau klik langsung pada peta di bawah — alamat akan terisi otomatis, dan tetap bisa kamu edit sendiri.</p>
                </div>

                {{-- Cari Lokasi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Cari Lokasi
                    </label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" id="search-address" placeholder="Ketik nama jalan/daerah, lalu tekan Enter"
                               class="flex-1 rounded-lg border-gray-300 text-sm">
                        <button type="button" id="btn-search" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm">Cari</button>
                    </div>
                    <p class="text-xs text-gray-400 mb-2">
                        Kalau alamat tidak ketemu, tidak masalah — cukup klik langsung titik lokasinya di peta di bawah.
                    </p>

                    <div id="map" class="w-full h-64 rounded-lg border border-gray-300"></div>
                    <p class="text-xs text-gray-400 mt-2">Klik langsung pada peta untuk menentukan lokasi.</p>

                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    <p id="coord-info" class="text-xs text-gray-500 mt-1">Belum memilih lokasi</p>
                </div>

                {{-- Tanggal & Jam Kunjungan --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan</label>
                        <input type="date" name="preferred_visit_date" value="{{ old('preferred_visit_date') }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Kunjungan</label>
                        <select name="preferred_visit_time" class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500">
                            <option value="">-- Pilih Waktu --</option>
                            <option value="Pagi (08.00-11.00)" {{ old('preferred_visit_time') === 'Pagi (08.00-11.00)' ? 'selected' : '' }}>Pagi (08.00 - 11.00)</option>
                            <option value="Siang (11.00-14.00)" {{ old('preferred_visit_time') === 'Siang (11.00-14.00)' ? 'selected' : '' }}>Siang (11.00 - 14.00)</option>
                            <option value="Sore (14.00-17.00)" {{ old('preferred_visit_time') === 'Sore (14.00-17.00)' ? 'selected' : '' }}>Sore (14.00 - 17.00)</option>
                        </select>
                    </div>
                </div>
                <p class="text-xs text-gray-400 -mt-2">
                    Jadwal final akan dikonfirmasi oleh Admin, menyesuaikan ketersediaan teknisi.
                </p>

                {{-- Catatan Tambahan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (opsional)</label>
                    <textarea name="notes" rows="2"
                              placeholder="Contoh: mohon datang setelah jam 3 sore, pagar rumah warna biru"
                              class="w-full rounded-lg border-gray-300 focus:ring-slate-500 focus:border-slate-500 text-sm">{{ old('notes') }}</textarea>
                </div>
            </div>
            {{-- ================= /KHUSUS HOME SERVICE ================= --}}

            <button type="submit" class="bg-slate-800 text-white px-5 py-2 rounded-lg hover:bg-slate-700">
                Kirim Permintaan Servis
            </button>
        </form>
    </div>

    {{-- Leaflet.js --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== Setup Peta =====
    const defaultLat = -0.3057;
    const defaultLng = 100.3692;

    const oldLat = {{ old('latitude') ? old('latitude') : 'null' }};
    const oldLng = {{ old('longitude') ? old('longitude') : 'null' }};

    const initialLat = oldLat ?? defaultLat;
    const initialLng = oldLng ?? defaultLng;

    const map = L.map('map').setView([initialLat, initialLng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    // Marker tunggal — dipindah, tidak pernah dibuat baru
    let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);
    window.addEventListener('home-service-map-visible', function () {
    setTimeout(function () {
        map.invalidateSize();
        map.setView(marker.getLatLng(), map.getZoom());
    }, 150); // delay kecil supaya x-transition selesai dulu
});

    const addressTextarea = document.getElementById('address-textarea');
    const coordInfo = document.getElementById('coord-info');
    const regionSelect = document.getElementById('region-select');

    // ===== Area Layanan Home Service (Bukittinggi + Agam, digabung) =====
    // PENTING: koordinat & radius di bawah PERKIRAAN — verifikasi via Google Maps
    // sebelum dipakai produksi. Titik dianggap "dalam jangkauan" kalau masuk
    // ke SALAH SATU lingkaran ini (gabungan Bukittinggi + Agam sebagai satu area).
    // PENTING: nama "name" di bawah HARUS PERSIS SAMA dengan
    // App\Http\Requests\StoreServiceRequest::VALID_REGIONS (PHP), termasuk ejaan
    // dan kapitalisasi, karena dipakai untuk auto-isi <select name="region">.
    const serviceAreaCircles = [
        // ===== Kota Bukittinggi (3 kecamatan) =====
        { name: 'Mandiangin Koto Selayan', lat: -0.2914, lng: 100.3756, radius_km: 3 },
        { name: 'Guguk Panjang',           lat: -0.3053, lng: 100.3734, radius_km: 2.5 },
        { name: 'Aur Birugo Tigo Baleh',   lat: -0.3211, lng: 100.3893, radius_km: 3 },

        // ===== Kabupaten Agam — kecamatan (koordinat terverifikasi) =====
        { name: 'Ampek Angkek',    lat: -0.2892, lng: 100.4290, radius_km: 5 },
        { name: 'Baso',            lat: -0.2835, lng: 100.4775, radius_km: 5 },
        { name: 'Banuhampu',       lat: -0.3561, lng: 100.3862, radius_km: 5 },
        { name: 'Tilatang Kamang', lat: -0.2534, lng: 100.3965, radius_km: 6 },
        { name: 'Sungai Pua',      lat: -0.3728, lng: 100.4012, radius_km: 4 },
        { name: 'Canduang',        lat: -0.3194, lng: 100.4381, radius_km: 4 },
        { name: 'IV Koto',         lat: -0.3325, lng: 100.3186, radius_km: 6 },

        // ===== Nagari di Kec. Banuhampu/IV Koto — TITIK PERKIRAAN, minta ayah verifikasi via Google Maps =====
        { name: 'Padang Lua',    lat: -0.3400, lng: 100.3650, radius_km: 3 }, // perkiraan, dekat Banuhampu
        { name: 'Sungai Landia', lat: -0.3350, lng: 100.3300, radius_km: 3 }, // perkiraan, dekat IV Koto
        { name: 'Gantiang',      lat: -0.3450, lng: 100.3600, radius_km: 3 }, // perkiraan
        { name: 'Ladang Laweh',  lat: -0.3500, lng: 100.3750, radius_km: 3 }, // perkiraan, dekat Banuhampu
        { name: 'Cingkariang',   lat: -0.3550, lng: 100.3700, radius_km: 3 }, // perkiraan, dekat Banuhampu
    ];
    // null = belum dicek, true = dalam jangkauan, false = di luar jangkauan
    window.isLocationInRange = null;

    // ===== Fungsi Geofencing (Haversine) =====
    function distanceKm(lat1, lng1, lat2, lng2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a =
            Math.sin(dLat / 2) ** 2 +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Cari nama wilayah pertama yang cocok (null kalau di luar semua area)
    function findMatchingRegion(lat, lng) {
        const match = serviceAreaCircles.find(area => distanceKm(lat, lng, area.lat, area.lng) <= area.radius_km);
        return match ? match.name : null;
    }

    // Cek gabungan: true kalau titik masuk ke SALAH SATU lingkaran (Bukittinggi ATAU Agam)
    function isInServiceArea(lat, lng) {
        return findMatchingRegion(lat, lng) !== null;
    }

    // ===== Reverse Geocoding (Nominatim) — versi lebih lengkap =====
    function reverseGeocode(lat, lng) {
        return fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(res => res.json())
            .then(data => {
                if (data && data.address) {
                    addressTextarea.value = buildFullAddress(data.address, data.display_name);
                } else if (data && data.display_name) {
                    addressTextarea.value = data.display_name;
                }
            })
            .catch(() => {
                // Reverse geocoding gagal — biarkan alamat manual (jika ada) tetap dipakai
            });
    }

    // Susun alamat dari komponen supaya lebih lengkap & rapi
    function buildFullAddress(addr, fallback) {
        const parts = [];

        // Jalan + nomor rumah
        const street = [addr.road, addr.house_number].filter(Boolean).join(' No. ');
        if (street) parts.push(street);

        // Detail area kecil (kalau ada) — RT/RW, dusun, gang, dll
        if (addr.neighbourhood) parts.push(addr.neighbourhood);
        if (addr.hamlet) parts.push(addr.hamlet);

        // Kelurahan/desa
        const village = addr.village || addr.suburb || addr.quarter;
        if (village) parts.push(village);

        // Kecamatan
        if (addr.suburb && village !== addr.suburb) parts.push(addr.suburb);
        if (addr.county && addr.county !== addr.city) parts.push(addr.county);

        // Kota/Kabupaten
        const city = addr.city || addr.town || addr.municipality;
        if (city) parts.push(city);

        // Provinsi
        if (addr.state) parts.push(addr.state);

        const result = parts.filter(Boolean).join(', ');
        return result || fallback || '';
    }

    // Dipanggil setiap kali marker berpindah (klik peta, drag, hasil pencarian)
    function updateCoords(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);

        const matchedRegion = findMatchingRegion(lat, lng);
        if (matchedRegion && regionSelect) {
            regionSelect.value = matchedRegion; // auto-pilih kalau ada opsi yang cocok persis
        }

        const inRange = matchedRegion !== null;
        window.isLocationInRange = inRange;

        if (inRange) {
            coordInfo.innerText = `Lokasi dipilih: ${lat.toFixed(6)}, ${lng.toFixed(6)} — Dalam jangkauan Home Service ✅`;
            coordInfo.className = 'text-xs text-green-600 mt-1 font-medium';
        } else {
            coordInfo.innerText = `Lokasi dipilih: ${lat.toFixed(6)}, ${lng.toFixed(6)} — Di luar jangkauan Home Service ⚠️`;
            coordInfo.className = 'text-xs text-red-600 mt-1 font-medium';

            Swal.fire({
                icon: 'warning',
                title: 'Home Service Belum Tersedia',
                html: 'Maaf.<br><br>' +
                      'Titik lokasi yang Anda pilih berada di luar wilayah operasional Home Service Jack Service Elektronik (Bukittinggi dan area sekitar Kabupaten Agam).<br><br>' +
                      'Silakan pilih titik lain yang sesuai, atau gunakan layanan Workshop.',
                confirmButtonColor: '#1e293b',
            });
        }

        reverseGeocode(lat, lng);
    }

    marker.on('dragend', function () {
        const pos = marker.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
        marker.setLatLng(e.latlng); // marker lama dipindah, bukan marker baru
        updateCoords(e.latlng.lat, e.latlng.lng);
    });

    if (oldLat && oldLng) {
        updateCoords(oldLat, oldLng);
    }

    // ===== Auto-geolocation saat page load — SENYAP, TIDAK memunculkan alert =====
    if (navigator.geolocation && !oldLat) {
        navigator.geolocation.getCurrentPosition(function (pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            map.setView([lat, lng], 16);
            marker.setLatLng([lat, lng]);

            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);

            const matchedRegion = findMatchingRegion(lat, lng);
            if (matchedRegion && regionSelect) {
                regionSelect.value = matchedRegion;
            }

            const inRange = matchedRegion !== null;

            if (inRange) {
                // Aksi otomatis (bukan aksi sadar user) -> netral, TIDAK memblokir submit,
                // supaya user tetap bisa geser sendiri sebelum submit
                window.isLocationInRange = null;
                coordInfo.innerText = `Lokasi Anda saat ini: ${lat.toFixed(6)}, ${lng.toFixed(6)} ✅ (silakan sesuaikan titik jika bukan lokasi servis)`;
                coordInfo.className = 'text-xs text-green-600 mt-1 font-medium';
            } else {
                window.isLocationInRange = null;
                coordInfo.innerText = `Lokasi Anda saat ini: ${lat.toFixed(6)}, ${lng.toFixed(6)} — Silakan geser/klik titik lokasi servis yang sesuai di peta.`;
                coordInfo.className = 'text-xs text-gray-500 mt-1';
            }

            reverseGeocode(lat, lng);
        }, function () {
            // User menolak izin lokasi — biarkan saja, tetap pakai default Bukittinggi
        });
    }

    // ===== Pencarian Alamat =====
    document.getElementById('btn-search').addEventListener('click', searchAddress);
    document.getElementById('search-address').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchAddress();
        }
    });

    function searchAddress() {
        const query = document.getElementById('search-address').value;
        if (!query) return;

        const btn = document.getElementById('btn-search');
        btn.innerText = 'Mencari...';
        btn.disabled = true;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1`)
            .then(res => res.json())
            .then(data => {
                btn.innerText = 'Cari';
                btn.disabled = false;

                if (data.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Alamat Tidak Ditemukan',
                        text: 'Silakan klik langsung titik lokasi Anda di peta di bawah.',
                        confirmButtonColor: '#1e293b',
                    });
                    return;
                }

                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                updateCoords(lat, lng); // aksi user -> boleh alert & memblokir kalau di luar jangkauan
            })
            .catch(() => {
                btn.innerText = 'Cari';
                btn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mencari',
                    text: 'Silakan klik langsung titik lokasi Anda di peta di bawah.',
                    confirmButtonColor: '#1e293b',
                });
            });
    }

    // ===== Preview Foto =====
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');

    photoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                photoPreview.src = event.target.result;
                photoPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            photoPreview.classList.add('hidden');
        }
    });

    // ===== Validasi Submit Form =====
    const serviceForm = document.querySelector('form[action="{{ route('pelanggan.services.store') }}"]');

    serviceForm.addEventListener('submit', function (e) {
        const serviceType = document.querySelector('input[name="service_type"]:checked').value;

        if (serviceType === 'Home Service') {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;

            // 0) Wilayah wajib sudah dipilih (jaga-jaga di sisi client, selaras dengan required_if di server)
            if (regionSelect && !regionSelect.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Wilayah Belum Dipilih',
                    text: 'Silakan pilih wilayah Home Service, atau klik lokasi di peta agar terisi otomatis.',
                    confirmButtonColor: '#1e293b',
                });
                return;
            }

            // 1) Lokasi wajib sudah dipilih
            if (!lat || !lng) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Lokasi Belum Dipilih',
                    text: 'Silakan pilih lokasi pada peta.',
                    confirmButtonColor: '#1e293b',
                });
                return;
            }

            // 2) Lokasi wajib dalam jangkauan Bukittinggi + Agam (hasil aksi sadar user)
            if (window.isLocationInRange === false) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Home Service Belum Tersedia',
                    html: 'Maaf.<br><br>' +
                          'Titik lokasi yang Anda pilih berada di luar wilayah operasional Home Service (Bukittinggi dan area sekitar Kabupaten Agam).<br><br>' +
                          'Silakan sesuaikan titik lokasi di peta, atau gunakan layanan Workshop.',
                    confirmButtonColor: '#1e293b',
                });
                return;
            }
        }
    });
});
    </script>

</x-dashboardlayout>