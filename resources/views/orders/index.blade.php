<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Daftar Pesanan</h3>

                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-4 border-b">ID Pesanan</th>
                                        <th class="py-2 px-4 border-b">Total Harga</th>
                                        <th class="py-2 px-4 border-b">Status</th>
                                        <th class="py-2 px-4 border-b">Tanggal Dibuat</th>
                                        <th class="py-2 px-4 border-b">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-2 px-4 border-b">{{ $order->order_id }}</td>
                                            <td class="py-2 px-4 border-b">Rp
                                                {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <span
                                                    class="px-2 py-1 rounded text-sm {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $order->status == 'pending' ? 'Menunggu' : ($order->status == 'paid' ? 'Dibayar' : 'Gagal') }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">{{ $order->created_at->format('d M Y H:i') }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('orders.show', $order) }}"
                                                    class="text-blue-600 hover:text-blue-800">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Belum ada pesanan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
