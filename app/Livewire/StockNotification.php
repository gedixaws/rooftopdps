<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;

class StockNotification extends Component
{

  
    // public $products;

    // protected $listeners = ['productUpdated' => 'checkStock'];

    // public function mount()
    // {
    //     if (!auth()->check() || !auth()->user()) {
    //         return;
    //     }
    
    //     $this->checkStock();
    // }

    // public function checkStock()
    // {

    //     if (!auth()->check() || !auth()->user()) {
    //         return;
    //     }

    //     $this->products = Product::where('stock', '<', 10)->get();

    //     // Ambil daftar produk yang sudah pernah dikirim notifikasi dari cache
    //     $notifiedProducts = Cache::get('notified_products', []);
    //     // dd($notifiedProducts);
    //     foreach ($this->products as $product) {
    //         $productId = $product->id;
    //         $currentStock = $product->stock;

    //         // Jika stok pernah naik ke 10 atau lebih, hapus dari cache
    //         if (isset($notifiedProducts[$productId]) && $currentStock >= 10) {
    //             unset($notifiedProducts[$productId]);
    //         }

 
    //         if (!isset($notifiedProducts[$productId])) {
    //             // Kirimkan notifikasi
    //             Notification::make()
    //                 ->title("Stok rendah: {$product->foods_id}")
    //                 ->body("Stok untuk produk {$product->name} kurang dari 10!")
    //                 ->warning()
    //                 ->sendToDatabase(auth()->user());

               
    //             $this->dispatch('show-toast', [
    //                 'message' => "Stok rendah untuk produk {$product->name}!",
    //                 'type' => 'warning'
    //             ]);

             
    //             $notifiedProducts[$productId] = $currentStock;
    //             Cache::put('notified_products', $notifiedProducts, now()->addHours(3));

    //             break; // Hentikan setelah satu notifikasi terkirim
    //         }
    //     }
   
    // }

    // public function render()
    // {
    //     return view('livewire.stock-notification');
    // }
}
