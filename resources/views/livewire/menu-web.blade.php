<div>

    <div class="text-center mt-10">
        <h2 class="text-4xl font-bold text-gray-800 underline decoration-gray-400 decoration-2">Our Menu</h2>
        <p class="text-gray-500 mt-1">Food & Drinks</p>
    </div>


    <div class="container mx-auto px-6 md:px-12 mt-10">
        @foreach ($categories as $category)
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">{{ $category->name }}</h2>


                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    {{-- Makanan --}}
                    @foreach ($category->foods as $food)
                        @if ($food->variants->count())
                            {{-- Jika memiliki varian, tampilkan setiap varian sebagai card terpisah --}}
                            @foreach ($food->variants as $variant)
                                <div class="bg-white shadow-md rounded-xl overflow-hidden border">
                                    <div class="relative p-3">
                                        <img src="{{ $food->product->image_url ?? 'default.jpg' }}"
                                            alt="{{ $variant->name }}" class="w-full h-32 object-contain"
                                            loading="lazy">
                                    </div>
                                    <div class="p-3 text-center">
                                        <livewire:product-stock :productId="$food->product->id" />
                                        {{-- <p class="text-gray-500 text-xs">Stock: {{ $food->product->stock ?? 0 }}</p> --}}
                                        <h2 class="text-sm font-semibold mt-1">{{ $food->name }} - {{ $variant->name }}</h2>
                                        <p class="text-lg font-bold text-gray-800 mt-2">
                                            Rp{{ number_format($variant->price, 0, ',', '.') }}
                                        </p>
                                        <button wire:click="addToCart('food', {{ $food->id }}, {{ $variant->id }})"
                                            class="mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition disabled:bg-gray-400 disabled:text-gray-600 disabled:cursor-not-allowed"
                                            @if(($food->product->stock ?? 0) <= 0) disabled @endif>
                                            Add to Cart
                                        </button>

                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Jika tidak memiliki varian, tampilkan produk utama --}}
                            <div class="bg-white shadow-md rounded-xl overflow-hidden border">
                                <div class="relative p-3">
                                    <img src="{{ $food->product->image_url ?? 'default.jpg' }}"
                                        alt="{{ $food->name }}" class="w-full h-32 object-contain" loading="lazy">
                                </div>
                                <div class="p-3 text-center">
                                    <livewire:product-stock :productId="$food->product->id" />
                                    {{-- <p class="text-gray-500 text-xs">Stock: {{ $food->product->stock ?? 0 }}</p> --}}
                                    <h2 class="text-sm font-semibold mt-1">{{ $food->name }}</h2>
                                    <p class="text-lg font-bold text-gray-800 mt-2">
                                        Rp{{ number_format($food->price, 0, ',', '.') }}
                                    </p>
                                    <button wire:click.prevent="addToCart('food', {{ $food->id }})"
                                        class="mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition disabled:bg-gray-400 disabled:text-gray-600 disabled:cursor-not-allowed"
                                        @if(($food->product->stock ?? 0) <= 0) disabled @endif>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    {{-- Minuman --}}
                    @foreach ($category->drinks as $drink)
                        @if ($drink->sizes->count())
                            {{-- Jika memiliki ukuran, tampilkan setiap ukuran sebagai card terpisah --}}
                            @foreach ($drink->sizes as $size)
                                <div class="bg-white shadow-md rounded-xl overflow-hidden border">
                                    <div class="relative p-3">
                                        <img src="{{ $drink->product->image_url ?? 'default.jpg' }}"
                                            alt="{{ $size->size }}" class="w-full h-32 object-contain"
                                            loading="lazy">
                                    </div>
                                    <div class="p-3 text-center">
                                        <livewire:product-stock :productId="$drink->product->id" />
                                        {{-- <p class="text-gray-500 text-xs">Stock: {{ $drink->product->stock ?? 0 }}</p> --}}
                                        <h2 class="text-sm font-semibold mt-1">{{ $drink->name }} -
                                            {{ $size->size }}</h2>
                                        <p class="text-lg font-bold text-gray-800 mt-2">
                                            Rp{{ number_format($size->price, 0, ',', '.') }}
                                        </p>
                                        <button
                                            wire:click="addToCart('drink', {{ $drink->id }}, {{ $size->id }})"
                                            class="mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition disabled:bg-gray-400 disabled:text-gray-600 disabled:cursor-not-allowed"
                                            @if(($drink->product->stock ?? 0) <= 0) disabled @endif>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Jika tidak memiliki ukuran, tampilkan produk utama --}}
                            <div class="bg-white shadow-md rounded-xl overflow-hidden border">
                                <div class="relative p-3">
                                    <img src="{{ $drink->product->image_url ?? 'default.jpg' }}"
                                        alt="{{ $drink->name }}" class="w-full h-32 object-contain" loading="lazy">
                                </div>
                                <div class="p-3 text-center">
                                    <livewire:product-stock :productId="$drink->product->id" />
                                    {{-- <p class="text-gray-500 text-xs">Stock: {{ $drink->product->stock ?? 0 }}</p> --}}
                                    <h2 class="text-sm font-semibold mt-1">{{ $drink->name }}</h2>
                                    <p class="text-lg font-bold text-gray-800 mt-2">
                                        Rp{{ number_format($drink->price, 0, ',', '.') }}
                                    </p>
                                    <button wire:click="addToCart('drink', {{ $drink->id }})"
                                        class="mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition disabled:bg-gray-400 disabled:text-gray-600 disabled:cursor-not-allowed"
                                        @if(($drink->product->stock ?? 0) <= 0) disabled @endif>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    
    </div>
    <!-- End Product List Component -->

</div>
