<?php

namespace App\Livewire;

use App\Models\Food;
use App\Models\Drink;
use Livewire\Component;
use App\Models\Category;
use App\Models\DrinkSize;
use App\Models\FoodVariant;
use Illuminate\Support\Facades\Session;

class MenuWeb extends Component
{
    public $alert;

    public $categories;
    public $cart = [];

    protected $listeners = ['cartUpdated' => 'updateCart'];

    public function mount()
    {
        // Ambil semua kategori beserta produk makanan dan minuman terkait
        $this->categories = Category::with([
            'foods.variants',
            'drinks.sizes'
        ])->get();

        $this->cart = session()->get('cart', []);
    }

    public function addToCart($type, $productId, $variantOrSizeId = null)
    {

        $cart = session()->get('cart', []);

        if ($type === 'food') {
            $product = Food::with('product')->find($productId);
            if (!$product) return;

            if ($variantOrSizeId) {
                $variant = FoodVariant::find($variantOrSizeId);
                if (!$variant) return;

                $itemId = "variant_{$variant->id}";
                $name = "{$product->name} - {$variant->name}";
                $price = $variant->price;
            } else {
                $itemId = "food_{$product->id}";
                $name = $product->name;
                $price = $product->product->price ?? $product->price ?? 0;
            }

            $cart[$itemId] = [
                'id' => $product->product->id ?? $product->id,
                'name' => $name,
                'price' => $price,
                'quantity' => ($cart[$itemId]['quantity'] ?? 0) + 1,
                'variant_id' => $variantOrSizeId ?? null,
                'size_id' => null,
                'image' => $product->product->image_url ?? asset('default.jpg'),
            ];
        } else {

            $product = Drink::with('product')->find($productId);
            if (!$product) return;

            if ($variantOrSizeId) {
                $size = DrinkSize::find($variantOrSizeId);
                if (!$size) return;

                $itemId = "size_{$size->id}";
                $name = "{$product->name} - {$size->size}";
                $price = $size->price;
            } else {
                $itemId = "drink_{$product->id}";
                $name = $product->name;
                $price = $product->product->price ?? $product->price ?? 0;
            }

            $cart[$itemId] = [
                'id' => $product->product->id ?? $product->id,
                'name' => $name,
                'price' => $price,
                'quantity' => ($cart[$itemId]['quantity'] ?? 0) + 1,
                'variant_id' => null,
                'size_id' => $variantOrSizeId ?? null,
                'image' => $product->product->image_url ?? asset('default.jpg'),
            ];
        }
        $this->alert = "Produk berhasil ditambahkan ke keranjang!";
        session()->put('cart', $cart); // Simpan cart ke session
        $this->dispatch('cartUpdated'); // Trigger Livewire update
        $this->dispatch('showAlert', ['message' => $this->alert]);
    }

    public function updateCart()
    {
        // Perbarui data cart setiap kali event cartUpdated diterima
        $this->cart = session()->get('cart', []);
    }


    public function render()
    {
        return view('livewire.menu-web', [
            'categories' => $this->categories,
        ]);
    }
}
