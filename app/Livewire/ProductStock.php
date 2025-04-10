<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductStock extends Component
{
    public $productId;
    public $stock;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->stock = Product::find($productId)->stock ?? 0;
    }

    public function refreshStock()
    {
        $this->stock = Product::find($this->productId)->stock ?? 0;
    }

    public function render()
    {
        return view('livewire.product-stock');
    }
}
