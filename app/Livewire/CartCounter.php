<?php

namespace App\Livewire;

use Livewire\Component;

class CartCounter extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $cart = session('cart', []);
        $count = array_sum(array_column($cart, 'quantity')); // Total jumlah produk

        return view('livewire.cart-counter', compact('count'));
    }
}
