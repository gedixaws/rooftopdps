<div x-data="{ open: false }">
    <button @click="open = true" class="fixed top-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-md shadow-lg">
        Cart (<span x-text="{{ count($orderItems) }}"></span>)
    </button>

    <!-- Shopping Cart Panel -->
    <div x-show="open" @click.away="open = false"
        class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform transition-transform"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">Shopping Cart</h2>
            <button @click="open = false" class="text-gray-600">&times;</button>
        </div>
        <div class="p-4 overflow-y-auto h-[80%]">
            @foreach ($orderItems as $index => $item)
                <div class="border-b py-2">
                    <p class="font-semibold">{{ $item['name'] }}
                        @if ($item['variant_id'])
                            - {{ \App\Models\Variant::find($item['variant_id'])->name }}
                        @endif
                        @if ($item['size_id'])
                            - {{ \App\Models\Size::find($item['size_id'])->size }}
                        @endif
                    </p>
                    <p class="text-gray-600">Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                    <p class="text-sm">Qty: {{ $item['quantity'] }}</p>
                    <button wire:click="removeItem({{ $index }})" class="text-red-500 text-xs">Hapus</button>
                </div>
            @endforeach

            <div class="mt-4">
                <label class="block text-sm font-semibold">Nama:</label>
                <input type="text" wire:model="name" class="w-full p-2 border rounded">
                <label class="block text-sm font-semibold mt-2">Catatan:</label>
                <textarea wire:model="note" class="w-full p-2 border rounded"></textarea>
            </div>
        </div>
        <div class="p-4 border-t">
            <p class="text-sm">Metode Pembayaran: <strong>Midtrans</strong></p>
            <button wire:click="checkout" class="w-full bg-gray-800 text-white py-2 mt-2 rounded">Checkout</button>
        </div>
    </div>
</div>

