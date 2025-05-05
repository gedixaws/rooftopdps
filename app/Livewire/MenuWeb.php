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
        // Ambil semua kategori beserta produk makanan dan minuman terkait sekaligus aktif atau tidak
        $this->categories = Category::with([
            'foods' => function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('is_active', true);
                })->with(['variants', 'product']);
            },
            'drinks' => function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('is_active', true);
                })->with(['sizes', 'product']);
            },
        ])->get();

        $this->cart = session()->get('cart', []);
    }

    public function addToCart($type, $productId, $variantOrSizeId = null)
    {

        $cart = session()->get('cart', []);

        if ($type === 'food') {
            $product = Food::with('product')->find($productId);
            if (!$product) return;

            $stock = $product->product->stock ?? $product->stock ?? 0; // Ambil stok produk makanan


            if ($variantOrSizeId) {
                $variant = FoodVariant::find($variantOrSizeId);
                if (!$variant) return;

                $itemId = "variant_{$variant->id}";
                $name = "{$product->name}";
                $price = $variant->price;
            } else {
                $itemId = "food_{$product->id}";
                $name = $product->name;
                $price = $product->product->price ?? $product->price ?? 0;
            }

            // Cek stok sebelum menambahkan ke keranjang
            $cartQuantity = $cart[$itemId]['quantity'] ?? 0;
            if ($cartQuantity >= $stock) {
                $this->dispatch('showAlert_stock');
                return;
            }

            $cart[$itemId] = [
                'id' => $product->product->id ?? $product->id,
                'name' => $name,
                'price' => $price,
                'quantity' => ($cart[$itemId]['quantity'] ?? 0) + 1,
                'food_variant_id' => $variantOrSizeId ?? null,
                'drink_size_id' => null,
            ];
        } else {

            $product = Drink::with('product')->find($productId);
            if (!$product) return;

            $stock = $product->product->stock ?? $product->stock ?? 0;

            if ($variantOrSizeId) {
                $size = DrinkSize::find($variantOrSizeId);
                if (!$size) return;

                $itemId = "size_{$size->id}";
                $name = "{$product->name}";
                $price = $size->price;
            } else {
                $itemId = "drink_{$product->id}";
                $name = $product->name;
                $price = $product->product->price ?? $product->price ?? 0;
            }

            // Cek stok sebelum menambahkan ke keranjang
            $cartQuantity = $cart[$itemId]['quantity'] ?? 0;
            if ($cartQuantity >= $stock) {
                $this->dispatch('showAlert_stock');
                return;
            }

            // Tambahkan ke keranjang jika stok masih ada
            $cart[$itemId] = [
                'id' => $product->product->id ?? $product->id,
                'name' => $name,
                'price' => $price,
                'quantity' => ($cart[$itemId]['quantity'] ?? 0) + 1,
                'food_variant_id' => null,
                'drink_size_id' => $variantOrSizeId ?? null,
            ];
        }

        session()->put('cart', $cart); // Simpan cart ke session
        $this->dispatch('cartUpdated'); // Trigger Livewire update
        $this->dispatch('showAlert_Added');
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
