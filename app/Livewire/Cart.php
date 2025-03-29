<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderProduct;

class Cart extends Component
{
    public $cart = [];
    public $name;
    public $note;

    protected $listeners = ['cartUpdated' => 'loadCart'];

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

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja kosong.');
            return;
        }

        // Generate Transaction ID & Slug
        $transactionId = Order::generateTransactionId();
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
            'payment_method_id' => 3, // Midtrans (default)
            'paid_amount' => $totalPrice,
            'change_amount' => 0,
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
        session()->flash('success', 'Checkout berhasil!');

    }

    public function render()
    {
        return view('livewire.cart');
    }

    
}
