<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generatePDF($orderId)
    {
        // Ambil data order berdasarkan ID
        $order = Order::findOrFail($orderId);

        // Render view untuk invoice
        $pdf = Pdf::loadView('invoices.order', compact('order'));

        // Download atau tampilkan PDF
        return $pdf->stream('invoice-' . $order->id . '.pdf');
    }

    public function index()
    {
        $orders = Order::all(); // Ambil semua data orders

        return view('order', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderProducts.product')->findOrFail($id);
        return view('order.invoice', compact('order'));
    }
}
