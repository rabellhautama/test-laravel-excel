<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        
        // Ambil semua item di cart untuk user yang sedang login
        $cartItems = Cart::with('product') ->where('user_id', Auth::id()) ->get();
        // Hitung jumlah item di cart
        $cartCount = $cartItems->sum('quantity');

        return view('cart.index', compact('cartItems', 'cartCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        

        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        // Tambahkan ke cart
        $cart = Cart::updateOrCreate (
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->input('quantity', 1),
                'total_price' => Product::find($request->product_id)->price * $request->input('quantity', 1),
            ]
        );

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Update item di cart
        $cartItem = Cart::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->total_price = $cartItem->product->price * $request->quantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus item dari cart
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }
    public function checkout(Request $request)
{
    $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Cart kamu masih kosong.');
    }

    // Simpan order
    $order = \App\Models\Order::create([
        'order_id' => 'ORD-' . time(),
        'user_id' => Auth::id(),
        'total_price' => $cartItems->sum('total_price'),
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);

    // Simpan detail item
    foreach ($cartItems as $item) {
        $order->orderDetails()->create([
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);
    }

    // Kosongkan cart
    Cart::where('user_id', Auth::id())->delete();

    return redirect()->route('orders.index')
        ->with('success', 'Checkout berhasil! Order ID: ' . $order->order_id);
    }
}
