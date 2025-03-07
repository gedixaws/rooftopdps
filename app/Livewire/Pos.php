<?php

namespace App\Livewire;

use Filament\Forms;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderProduct;

use Filament\Forms\Set;
use Livewire\Component;
use Filament\Forms\Form;


use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class Pos extends Component implements HasForms
{
    use InteractsWithForms;
    public $search = '';
    public $name_customer = '';
    public $payment_method_id = 0;
    public $payment_methods;
    public $order_items = [];
    public $total_price;

    public function render()
    {
        return view('livewire.pos', [
            'products' => Product::where('stock', '>', 0)
                ->search($this->search)
                ->paginate(12),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Checkout')
                    ->schema([
                        Forms\Components\TextInput::make('name_customer')
                            ->required()
                            ->maxLength(50)
                            ->default(fn() => $this->name_customer),
                    ]),
                Forms\Components\TextInput::make('total_price')
                    ->readOnly()
                    ->numeric()
                    ->default(fn() => $this->total_price),
                Forms\Components\Select::make('payment_method_id')
                    ->required()
                    ->options($this->payment_methods->pluck('name', 'id'))
                    ->label('Payment Method'),
            ]);
    }

    // Untuk simpan data sementara dari dalam session supaya disaat refresh data tidak hilang
    public function mount()
    {
        if (session()->has('orderItems')) {
            $this->order_items = session('orderItems');
        }
        $this->payment_methods = PaymentMethod::all();
        $this->form->fill(['payment_methods' => $this->payment_methods]);
    }

    public function addToOrder($productId)
    {
        $product = Product::find($productId);
        if ($product->stock <= 0) {
            Notification::make()
                ->title('Stok Habis')
                ->danger()
                ->send();
            return;
        }

        $existingItemKey = null;
        foreach ($this->order_items as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItemKey = $key;
                break;
            }
        }

        if ($existingItemKey !== null) {
            $this->order_items[$existingItemKey]['quantity']++;
        } else {
            $this->order_items[] = [
                'product_id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'quantity' => 1
            ];
        }

        session()->put('orderItems', $this->order_items);
        Notification::make()
            ->title('Produk Ditambahkan ke Keranjang')
            ->success()
            ->send();
    }

    public function loadOrderItems($orderItems)
    {
        $this->order_items = $orderItems;
        session()->put('orderItems', $orderItems);
    }

    public function increaseQuantity($product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            Notification::make()
                ->title('Product Tidak Ditemukan')
                ->danger()
                ->send();
            return;
        }

        foreach ($this->order_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                if ($item['quantity'] + 1 <= $product->stock) {
                    $this->order_items[$key]['quantity']++;
                } else {
                    Notification::make()
                        ->title('Stok Tidak Mencukupi')
                        ->danger()
                        ->send();
                }
                break;
            }
        }
        session()->put('orderItems', $this->order_items);
    }

    public function decreaseQuantity($product_id)
    {
        foreach ($this->order_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                if ($this->order_items[$key]['quantity'] > 1) {
                    $this->order_items[$key]['quantity']--;
                } else {
                    unset($this->order_items[$key]);
                    $this->order_items = array_values($this->order_items);
                }
                break;
            }
        }
        session()->put('orderItems', $this->order_items);
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->order_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $this->total_price = $total;
        return $total;
    }

    public function checkout()
    {
        $this->validate([
            'name_customer' => 'required|string|max:50',
            'payment_method_id' => 'required'
        ]);
        $payment_method_id_temp = $this->payment_method_id;

        $order = Order::create([
            'name' => $this->name_customer,
            'total_price' => $this->calculateTotal(),
            'payment_method_id' => $payment_method_id_temp
        ]);

        foreach ($this->order_items as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price']
            ]);
        }

        $this->order_items = [];
        session()->forget(['orderItems']);

        return redirect()->to('admin/orders');

    }
}
