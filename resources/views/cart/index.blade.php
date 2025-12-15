<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- STEP PEMBAYARAN --}}
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="flex justify-center space-x-10">
                    {{-- Step 1: Keranjang (aktif) --}}
                    <div class="flex flex-col items-center text-sm">
                        <div class="flex items-center justify-center w-9 h-9 rounded-full bg-indigo-500 text-white font-semibold">
                            1
                        </div>
                        <span class="mt-2 font-medium text-gray-900">Keranjang</span>
                    </div>

                    {{-- Step 2: Review Order --}}
                    <div class="flex flex-col items-center text-sm">
                        <div class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-300 text-gray-700 font-semibold">
                            2
                        </div>
                        <span class="mt-2 text-gray-700">Review Order</span>
                    </div>

                    {{-- Step 3: Pembayaran --}}
                    <div class="flex flex-col items-center text-sm">
                        <div class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-300 text-gray-700 font-semibold">
                            3
                        </div>
                        <span class="mt-2 text-gray-700">Pembayaran</span>
                    </div>
                </div>
            </div>

            {{-- KERANJANG --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Keranjang Belanja Anda
                </h3>

                @if($cartItems->isEmpty())
                    <div class="py-10 text-center text-gray-500">
                        Keranjang belanja Anda kosong. Mulailah berbelanja sekarang!
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-left text-gray-700">
                                    <th class="px-4 py-2 font-semibold">#</th>
                                    <th class="px-4 py-2 font-semibold">Nama Produk</th>
                                    <th class="px-4 py-2 font-semibold">Kuantitas</th>
                                    <th class="px-4 py-2 font-semibold">Harga</th>
                                    <th class="px-4 py-2 font-semibold">Total Harga</th>
                                    <th class="px-4 py-2 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PUT')
                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    value="{{ $item->quantity }}"
                                                    min="1"
                                                    class="w-20 border-gray-300 rounded-md shadow-sm text-sm"
                                                >
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                >
                                                    Update
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            Rp{{ number_format($item->product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            Rp{{ number_format($item->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <form
                                                action="{{ route('cart.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus produk ini dari keranjang?')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Ringkasan --}}
                    <div class="mt-6 border-t pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="space-y-1 text-sm text-gray-700">
                            <div>
                                <span class="font-semibold">Total Item:</span>
                                <span>{{ $cartCount }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">Total Harga:</span>
                                <span>Rp{{ number_format($cartItems->sum('total_price'), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('cart.checkout') }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Lanjutkan ke Review Order
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
