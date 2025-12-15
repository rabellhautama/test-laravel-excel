<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peta Belanja') }}
        </h2>
    </x-slot>

    <x-slot name="app_asset">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-4 flex items-center gap-4">
                    <div>
                        <label class="block font-bold text-sm text-gray-700">Radius (KM)</label>
                        <input type="number" id="radius" min="1" max="50" value="10" class="border-gray-300 rounded-md shadow-sm">
                    </div>
                    <button id="locateMe" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-5">
                        Cari di Sekitar Saya
                    </button>
                    <a href="{{ route('products.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-5 ml-auto">
                        + Jual Produk
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
                    </div>
                    <div class="col-span-1" id="product_list" style="height: 500px; overflow-y: auto;">
                        <p class="text-gray-500 text-center mt-10">Klik "Cari di Sekitar Saya" untuk melihat produk.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // 1. Inisialisasi Peta (Default Jakarta)
        var map = L.map('map').setView([-6.200000, 106.816666], 13);
        var userMarker;
        var markersLayer = L.layerGroup().addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // 2. Ambil Data Produk dari Laravel
        function fetchProducts(lat, lng, rad) {
            markersLayer.clearLayers(); // Hapus marker lama

            fetch(/products?latitude=${lat}&longitude=${lng}&radius=${rad})
                .then(response => response.json())
                .then(data => {
                    let listHtml = "";

                    if (data.length === 0) {
                        listHtml = "<p class='text-red-500 p-2'>Tidak ada produk dalam radius ini.</p>";
                    }

                    data.forEach(product => {
                        // Buat Marker
                        const marker = L.marker([product.latitude, product.longitude])
                            .bindPopup(<b>${product.name}</b><br>Rp ${new Intl.NumberFormat().format(product.price)});
                        markersLayer.addLayer(marker);

                        // Buat List Item
                        listHtml += `
                            <div class="border p-4 mb-2 rounded hover:bg-gray-50">
                                <h3 class="font-bold text-lg">${product.name}</h3>
                                <p class="text-sm text-gray-600">${product.description}</p>
                                <p class="font-bold text-green-600 mt-1">Rp ${new Intl.NumberFormat().format(product.price)}</p>
                                <form action="{{ route('cart.store') }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="${product.id}">
                                    <div class="flex gap-2">
                                        <input type="number" name="quantity" value="1" min="1" class="w-16 text-sm border rounded p-1">
                                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Beli</button>
                                    </div>
                                </form>
                            </div>
                        `;
                    });

                    document.getElementById("product_list").innerHTML = listHtml;
                })
                .catch(err => console.error(err));
        }

        // 3. Cari Lokasi User
        document.getElementById('locateMe').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const { latitude, longitude } = position.coords;
                    const radius = document.getElementById("radius").value;

                    // Geser Peta
                    map.setView([latitude, longitude], 13);

                    // Marker User
                    if (userMarker) map.removeLayer(userMarker);
                    userMarker = L.marker([latitude, longitude]).addTo(map).bindPopup('Lokasi Anda').openPopup();

                    // Ambil Produk
                    fetchProducts(latitude, longitude, radius);
                }, () => {
                    alert('Gagal mengambil lokasi.');
                });
            } else {
                alert('Browser tidak mendukung Geolocation.');
            }
        });
    </script>
</x-app-layout>
