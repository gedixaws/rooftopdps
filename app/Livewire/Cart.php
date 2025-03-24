<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Session;

class Cart extends Component
{
    public $orderItems = [];
    public $name;
    public $note;

    protected $listeners = ['addToCart'];

    public function mount()
    {
        $this->orderItems = Session::get('orderItems', []);
    }


    public function removeItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
        Session::put('orderItems', $this->orderItems);
        $this->emit('cartUpdated');
    }

    public function checkout()
    {
        if (empty($this->orderItems)) {
            session()->flash('error', 'Keranjang belanja kosong.');
            return;
        }

        $order = Order::create([
            'customer_name' => $this->name,
            'note' => $this->note,
            'payment_method' => 'Midtrans',
            'status' => 'pending',
        ]);

        foreach ($this->orderItems as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'],
                'size_id' => $item['size_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        Session::forget('orderItems');
        $this->orderItems = [];
        $this->name = '';
        $this->note = '';

        session()->flash('success', 'Pesanan berhasil dibuat.');
        $this->emit('cartUpdated');
    }

    public function render()
    {
        return view('livewire.cart');
    }
}