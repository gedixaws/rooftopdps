<div>
    <div class="p-4 overflow-y-auto h-[80%]">
        @foreach ($cart as $index => $item)
            <div class="border-b py-2">
                <p class="font-semibold">{{ $item['name'] }}
                    @if (!empty($item['variant_id']))
                        - {{ \App\Models\FoodVariant::find($item['variant_id'])->name }}
                    @endif
                    @if (!empty($item['size_id']))
                        - {{ \App\Models\DrinkSize::find($item['size_id'])->size }}
                    @endif
                </p>
                <p class="text-gray-600">Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                <p class="text-sm">Qty: {{ $item['quantity'] }}</p>
                <button wire:click="removeItem('{{ $index }}')" class="text-red-500 text-xs">Hapus</button>
            </div>
        @endforeach

        <div class="mt-4">
            <label class="block text-sm font-semibold" required>Nama:</label>
            <input type="text" wire:model="name" class="w-full p-2 border rounded">
            <label class="block text-sm font-semibold mt-2">Catatan:</label>
            <textarea wire:model="note" class="w-full p-2 border rounded"></textarea>
        </div>
    </div>

    <div class="p-4 border-t">
        <p class="text-sm">Metode Pembayaran: <strong>Midtrans</strong></p>
        <button wire:click="initiatePayment" class="w-full bg-gray-800 text-white py-2 mt-2 rounded">Checkout</button>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    
    <script>
        window.addEventListener('midtransPayment', event => {
            let snapToken = event.detail[0].token;
            
            if (!snapToken) {
                alert('Token pembayaran tidak tersedia!');
                return;
            }
    
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    Livewire.dispatch('paymentSuccess', result);
                },
                onPending: function(result) {
                    console.log('Pembayaran pending:', result);
                },
                onError: function(result) {
                    console.log('Pembayaran gagal:', result);
                }
            });
        });
    </script>
    
</div>
