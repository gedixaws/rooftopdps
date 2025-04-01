<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Transaction;
use App\Models\Order;

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
        $transactionStatus = $notification['transaction_status'];
        $orderId = $notification['order_id'];

        // Cari order berdasarkan transaction_id
        $order = Order::where('transaction_id', $orderId)->first();

        if (!$order) {
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        // Update status pembayaran berdasarkan status dari Midtrans
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->update(['payment_status' => 'paid']);
        } elseif ($transactionStatus == 'pending') {
            $order->update(['payment_status' => 'pending']);
        } elseif ($transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $order->update(['payment_status' => 'failed']);
        }

        return response()->json(['message' => 'Notifikasi berhasil diproses']);
    }
}
