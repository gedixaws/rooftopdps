<?php

namespace App\Livewire;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Session;

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
            $this->dispatch('showAlert_Remove'); // Emit event untuk notifikasi
        }
    }

    public function initiatePayment()
    {
        if (empty($this->cart)) {
            return $this->dispatch('showAlert_keranjang_kosong');
        }
        if (empty($this->name)) {
            return $this->dispatch('showAlert_keranjang_kosong2');
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Cek stok semua item
        foreach ($this->cart as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                return $this->dispatch('showAlert_stok_kurang', message: $item['name'] . " stok kosong. Silahkan pilih produk lain.");
            }
        }

        // Generate Transaction ID
        $transactionId = Order::generateTransactionId();
        $slug = Str::slug($transactionId, '-');
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
        $order = Order::create([
            'transaction_id' => $transactionId,
            'slug' => $slug,
            'total_price' => $totalPrice,
            'name' => $this->name . "-" . date('Y-m-d H:i:s'),
            'note' => $this->note,
            'payment_method_id' => 3, // Midtrans
            'paid_amount' => $totalPrice,
            'change_amount' => 0,
        ]);

        // Simpan ke tabel order_products
        foreach ($this->cart as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'food_variant_id' => $item['food_variant_id'] ?? null,
                'drink_size_id' => $item['drink_size_id'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        // Kirim event ke frontend
        $this->dispatch('midtransPayment', ['token' => $snapToken]);
    }


    public function render()
    {
        return view('livewire.cart');
    }
}
