<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function notificationHandler(Request $request)
    {
        $notification = $request->all();
    
        if ($notification['transaction_status'] === 'settlement') {
            DB::beginTransaction();
    
            try {
                // Ambil order dan kunci barisnya
                $order = Order::where('transaction_id', $notification['order_id'])->lockForUpdate()->firstOrFail();
    
                $orderProducts = OrderProduct::where('order_id', $order->id)->get();
    
                foreach ($orderProducts as $orderProduct) {
                    // Kunci produk biar tidak bisa diakses oleh transaksi lain
                    $product = Product::where('id', $orderProduct->product_id)->lockForUpdate()->firstOrFail();
    
                    // Cek apakah stok cukup
                    if ($product->stock < $orderProduct->quantity) {
                        // Kalau tidak cukup, mark order sebagai failed dan batalkan transaksi
                        $order->update(['status' => 'failed']);
                        DB::commit();
    
                        return response()->json([
                            'status' => 200,
                            'message' => 'Stock not sufficient. Order marked as failed.',
                        ]);
                    }
                }
    
                // Semua stok aman, kurangi stok
                foreach ($orderProducts as $orderProduct) {
                    $product = Product::where('id', $orderProduct->product_id)->lockForUpdate()->firstOrFail();
                    $product->decrement('stock', $orderProduct->quantity);
                }
    
                // Update status order
                $order->update(['status' => 'paid']);
                DB::commit();
                Cache::put('last_user_ping', [
                    'time' => now()->toDateTimeString(),
                    'order_id' => $order->id,
                    'transaction_id' => $order->transaction_id,
                ], 60);
                return response()->json([
                    'status' => 200,
                    'message' => 'Order processed successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
    
                return response()->json([
                    'status' => 500,
                    'message' => 'Error processing order',
                    'error' => $e->getMessage(),
                ]);
            }
        }
    
        // Jika expired
        if ($notification['transaction_status'] === 'expire') {
            Order::where('transaction_id', $notification['order_id'])->delete();
        }
    
        return response()->json([
            'status' => 200,
            'message' => 'Notification received.',
        ]);
    }

    public function invoice($slug)
    {
        $order = Order::where('transaction_id', $slug)->first();
        return view('cart', compact('order'));
    }
}
