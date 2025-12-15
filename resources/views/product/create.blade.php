<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jual Produk Baru') }}
        </h2>
    </x-slot>
    <x-slot name="app_asset">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                        <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                        <input type="number" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Lokasi Toko (Klik di Peta)</label>
                        <div id="map" style="height: 300px; width: 100%;" class="rounded border"></div>
                    </div>

                    <div class="flex gap-4 mb-4">
                        <div class="w-1/2">
                            <label>Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="w-full bg-gray-100 border rounded p-2" readonly required>
                        </div>
                        <div class="w-1/2">
                            <label>Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="w-full bg-gray-100 border rounded p-2" readonly required>
                        </div>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Simpan Produk
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([-6.200000, 106.816666], 13);
        var marker;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

        // Ambil Lokasi User Saat Ini
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                map.setView([position.coords.latitude, position.coords.longitude], 13);
            });
        }

        // Event Klik Peta
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    </script>
</x-app-layout>
