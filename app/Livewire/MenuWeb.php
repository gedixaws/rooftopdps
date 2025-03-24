<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class MenuWeb extends Component
{
    public $categories;

    public function mount()
    {
        // Ambil semua kategori beserta produk makanan dan minuman terkait
        $this->categories = Category::with([
            'foods.variants',
            'drinks.sizes'
        ])->get();
    }

    public function addToCart($productId, $variantId = null, $sizeId = null)
    {
        dd("Product ID: $productId", "Variant ID: $variantId", "Size ID: $sizeId");
        $product = Product::find($productId);
        
        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Produk tidak tersedia atau stok habis.');
            return;
        }

        $itemKey = collect($this->orderItems)->search(fn ($item) => 
            $item['product_id'] == $productId &&
            $item['variant_id'] == $variantId &&
            $item['size_id'] == $sizeId
        );

        if ($itemKey !== false) {
            $this->orderItems[$itemKey]['quantity']++;
        } else {
            $this->orderItems[] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'size_id' => $sizeId,
                'name' => $product->name,
                'price' => $variantId ? $product->variants->find($variantId)->price :
                          ($sizeId ? $product->sizes->find($sizeId)->price : $product->price),
                'quantity' => 1,
            ];
        }

        Session::put('orderItems', $this->orderItems);
        $this->emit('cartUpdated');
        
        
    }

    public function render()
    {
        return view('livewire.menu-web', [
            'categories' => $this->categories,
        ]);
    }
}
