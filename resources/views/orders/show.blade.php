<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center">
            {{ __('Detail Pesanan') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            {{-- Kolom Kiri (Data Pesanan) --}}
            <div class="col-12 col-md-8 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>{{ __('Data Pesanan') }}</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover table-condensed">
                            <tr>
                                <td id="td-id">{{ __('ID Pesanan') }}</td>
                                <td>{{ $orders->id }}</td>
                            </tr>
                            <tr>
                                <td id="td-total-harga">{{ __('Total Harga') }}</td>
                                <td>Rp {{ number_format($orders->total_price, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td id="td-status-orders">{{ __('Status Order') }}</td>
                                <td><span class="badge bg-primary">{{ $orders->status }}</span></td>
                            </tr>
                            <tr>
                                <td id="td-status-pembayaran">{{ __('Status Pembayaran') }}</td>
                                <td><span class="badge bg-warning">{{ $orders->payment_status }}</span></td>
                            </tr>
                            <tr>
                                <td id="td-tanggal">{{ __('Tanggal') }}</td>
                                <td>{{ $orders->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>

                        {{-- Tabel Item Pesanan --}}
                        <h5 class="mt-4">{{ __('Item Pesanan') }}</h5>
                        <table class="table table-striped">
                            {{-- ... Detail item pesanan akan ditampilkan di sini ... --}}
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan (Pembayaran) --}}
            <div class="col-12 col-md-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>{{ __('Pembayaran') }}</h5>
                    </div>
                    <div class="card-body">
                        @if($orders->payment_status == 'unpaid')
                            <button class="btn btn-primary w-100" id="pay-button">{{ __('Bayar Sekarang') }}</button>
                            <small class="text-muted d-block mt-2 text-center">{{ __('Lakukan Pembayaran') }}</small>
                        @elseif($orders->payment_status == 'paid')
                            <p class="text-success text-center">Pembayaran Berhasil!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="page_script">
        {{-- Script Midtrans Snap (Sandbox) --}}
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            // Ambil tombol "Bayar Sekarang"
            const payButton = document.querySelector('#pay-button');
            payButton.addEventListener('click', function(e) {
                e.preventDefault();

                // Panggil Midtrans Snap
                snap.pay('{{ $orders->snap_token }}', {
                    onSuccess: function(result) {
                        /* You may add your own implementation here */
                        alert('Pembayaran berhasil!');
                        console.log(result);
                        window.location.reload(); // Contoh: Muat ulang halaman setelah sukses
                    },
                    onPending: function(result) {
                        /* You may add your own implementation here */
                        alert('Pembayaran tertunda!');
                        console.log(result);
                    },
                    onError: function(result) {
                        /* You may add your own implementation here */
                        alert('Pembayaran gagal!');
                        console.log(result);
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>