<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use App\Models\OrderProduct;

class Cart extends Component
{
    public $cart = [];
    public $name;
    public $note;

    protected $listeners = ['cartUpdated' => 'loadCart', 'paymentSuccess'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = session()->get('cart', []) ?? [];
    }

    public function removeItem($index)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]); // Hapus item dari array
            session()->put('cart', $cart); // Perbarui session
            $this->cart = $cart; // Perbarui properti Livewire
            $this->dispatch('cartUpdated'); // Emit event agar UI diperbarui
        }
    }

    public function initiatePayment()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja kosong.');
            return;
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Generate Transaction ID
        $transactionId = Order::generateTransactionId();

        // Hitung total harga
        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $this->cart));

        // Buat parameter Midtrans
        $transactionDetails = [
            'transaction_details' => [
                'order_id' => $transactionId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $this->name ?? 'Pelanggan',
            ]
        ];

        // Dapatkan token pembayaran dari Midtrans
        $snapToken = Snap::getSnapToken($transactionDetails);

        // Kirim event ke frontend
        $this->dispatch('midtransPayment', ['token' => $snapToken]);
    }

    public function paymentSuccess($transactionResul = [])
    {
        if (!isset($transactionResult['transaction_status'])) {
            session()->flash('error', 'Data transaksi tidak valid.');
            return;
        }
    
        if ($transactionResult['transaction_status'] !== 'capture' && $transactionResult['transaction_status'] !== 'settlement') {
            session()->flash('error', 'Pembayaran gagal atau belum selesai.');
            return;
        }

        // Generate Transaction ID & Slug
        $transactionId = $transactionResult['order_id'];
        $slug = Str::slug($transactionId, '-');

        // Hitung total harga
        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $this->cart));

        // Simpan ke tabel orders
        $order = Order::create([
            'transaction_id' => $transactionId,
            'slug' => $slug,
            'total_price' => $totalPrice,
            'name' => $this->name,
            'note' => $this->note,
            'payment_method_id' => 3, // Midtrans
            'paid_amount' => $totalPrice,
            'change_amount' => 0,
            'payment_status' => 'paid',
        ]);

        // Simpan ke tabel order_products
        foreach ($this->cart as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'variant_id' => $item['variant_id'] ?? null,
                'size_id' => $item['size_id'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        // Kosongkan cart setelah checkout
        session()->forget('cart');
        $this->cart = [];
        $this->name = '';
        $this->note = '';

        $this->dispatch('cartUpdated');
        session()->flash('success', 'Pembayaran berhasil dan pesanan dicatat!');
    }

    public function render()
    {
        return view('livewire.cart');
    }

    
}
