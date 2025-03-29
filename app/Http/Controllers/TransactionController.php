<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function checkTransaction(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ], [
            'transaction_id.required' => 'ID transaksi harus diisi.',
            'transaction_id.string' => 'ID transaksi tidak valid.',
        ]);
    
        // Cari transaksi berdasarkan ID
        $order = Order::where('transaction_id', $request->transaction_id)->first();
    
        // Jika transaksi tidak ditemukan
        if (!$order) {
            return redirect()->back()->with('error', 'ID transaksi tidak ditemukan.');
        }
    
        // Redirect ke halaman detail transaksi
        return redirect()->route('transaction.show', ['slug' => $order->slug]);
    }

    public function show($slug)
    {
        $order = Order::where('slug', $slug)
        ->with([
            'orderProducts.product', 
            'orderProducts.foodVariant.product', // Ambil produk dari varian makanan
            'orderProducts.drinkSize.product',  // Ambil produk dari ukuran minuman
        ])
        ->firstOrFail();
    return view('transaction/transaction', compact('order'));
    }
}
