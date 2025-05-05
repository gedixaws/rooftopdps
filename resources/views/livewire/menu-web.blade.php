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
                    @foreach ($category->foods->sortBy('id') as $food)
                        <div wire:key="food-{{ $food->id }}" class="bg-white shadow-md rounded-xl overflow-hidden border">
                            <div class="relative p-3">
                                <img src="{{ $food->product->image_url ?? 'default.jpg' }}" alt="{{ $food->name }}"
                                    class="w-full h-32 object-contain" loading="lazy">
                            </div>
                            <div class="p-3 text-center">
                                {{-- Stock --}}
                                @if ($food->variants->count() > 0)
                                    {{-- Produk dengan varian --}}
                                    @php
                                        $firstVariant = $food->variants->first();
                                    @endphp
                                    <livewire:product-stock :productId="$food->product->id" :key="'food-product-stock-' . $food->product->id . '-' . $firstVariant->id" />
                                @else
                                    {{-- Produk tanpa varian --}}
                                    <livewire:product-stock :productId="$food->product->id" :key="'food-product-stock-' . $food->product->id" />
                                @endif

                                <h2 class="text-sm font-semibold mt-1">{{ $food->name }}</h2>

                                @if ($food->variants->count())
                                    {{-- Dengan VARIANT --}}
                                    @php
                                        $defaultVariant = $food->variants->first();
                                    @endphp

                                    <div x-data="{
                                        {{-- Inisialisasi harga varian default yang pertama, agar langsung tampil di awal --}}
                                        selectedPrice: {{ $defaultVariant->price }},
                                    
                                            {{-- Inisialisasi ID varian default (id pertama dari koleksi) --}}
                                        selectedVariant: {{ $defaultVariant->id }},
                                    
                                            {{-- // Fungsi untuk menangani perubahan opsi saat user memilih varian baru dari <select> --}}
                                        selectVariant(event) {
                                            {{-- // Ambil elemen option yang sedang dipilih dari dropdown --}}
                                            const option = event.target.options[event.target.selectedIndex];
                                            {{-- // Ambil nilai dari option yang dipilih (id varian) dan simpan ke variabel `selectedVariant` --}}
                                            this.selectedVariant = option.value;
                                            {{-- Ambil harga dari atribut data-price yang ada di <option> --}}
                                            this.selectedPrice = option.getAttribute('data-price');
                                        }
                                    }" wire:ignore class="mt-2">
                                        <select @change="selectVariant($event)"
                                            class="border border-gray-300 rounded px-2 py-1 text-sm w-full text-gray-700 focus:ring focus:ring-gray-300">
                                            @foreach ($food->variants as $variant)
                                                <option value="{{ $variant->id }}" data-price="{{ $variant->price }}"
                                                    @if ($loop->first) selected @endif>
                                                    {{ $variant->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <p class="text-lg font-bold text-gray-800 mt-2">
                                            Rp<span x-text="parseInt(selectedPrice).toLocaleString('id-ID')"></span>
                                        </p>

                                        <button
                                            class="font-bold mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition disabled:font-medium disabled:bg-white disabled:text-gray-500 disabled:border-gray-300 disabled:cursor-not-allowed"
                                            x-on:click="$wire.addToCart('food', {{ $food->id }}, selectedVariant)"
                                            @if (($food->product->stock ?? 0) <= 0) disabled @endif>
                                            Add to Cart
                                        </button>
                                    </div>
                                @else
                                    {{-- TANPA VARIANT --}}
                                    @php
                                        $price = $food->product->price ?? ($food->price ?? 0);
                                    @endphp

                                    <p class="text-lg font-bold text-gray-800 mt-2">
                                        Rp{{ number_format($price, 0, ',', '.') }}
                                    </p>

                                    <button
                                        class="font-bold mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition disabled:font-medium disabled:bg-white disabled:text-gray-500 disabled:border-gray-300 disabled:cursor-not-allowed"
                                        wire:click="addToCart('food', {{ $food->id }})"
                                        @if (($food->product->stock ?? 0) <= 0) disabled @endif>
                                        Add to Cart
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Minuman --}}
                    @foreach ($category->drinks->sortBy('id') as $drink)
                        <div wire:key="drink-{{ $drink->id }}" class="bg-white shadow-md rounded-xl overflow-hidden border">
                            <div class="relative p-3">
                                <img src="{{ $drink->product->image_url ?? 'default.jpg' }}" alt="{{ $drink->name }}"
                                    class="w-full h-32 object-contain" loading="lazy">
                            </div>
                            <div class="p-3 text-center">
                                {{-- Stock --}}
                                @if ($drink->sizes->count() > 0)
                                    {{-- Produk dengan varian --}}
                                    @php
                                        $firstSizes = $drink->sizes->first();
                                    @endphp
                                    <livewire:product-stock :productId="$drink->product->id" :key="'drink-product-stock-' . $drink->product->id . '-' . $firstSizes->id" />
                                @else
                                    {{-- Produk tanpa varian --}}
                                    <livewire:product-stock :productId="$drink->product->id" :key="'drink-product-stock-' . $drink->product->id" />
                                @endif

                                <h2 class="text-sm font-semibold mt-1">{{ $drink->name }}</h2>

                                @if ($drink->sizes->count())
                                    {{-- DENGAN SIZE --}}
                                    @php
                                        $defaultSize = $drink->sizes->first();
                                    @endphp

                                    <div x-data="{
                                        selectedPrice: {{ $defaultSize->price }},
                                        selectedSize: {{ $defaultSize->id }},
                                        selectSize(id, price) {
                                            this.selectedSize = id;
                                            this.selectedPrice = price;
                                        }
                                    }" wire:ignore class="mt-2">
                                        <div class="flex justify-center gap-2 flex-wrap">
                                            @foreach ($drink->sizes as $size)
                                                <label class="text-sm cursor-pointer">
                                                    <input type="radio" name="size-{{ $drink->id }}"
                                                        class="accent-black focus:ring-black"
                                                        value="{{ $size->id }}"
                                                        x-on:change="selectSize({{ $size->id }}, {{ $size->price }})"
                                                        @if ($loop->first) checked @endif>
                                                    {{ $size->size }}
                                                </label>
                                            @endforeach
                                        </div>

                                        <p class="text-lg font-bold text-gray-800 mt-2">
                                            Rp<span x-text="selectedPrice.toLocaleString('id-ID')"></span>
                                        </p>

                                        <button
                                            class="font-bold mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition disabled:font-medium disabled:bg-white disabled:text-gray-500 disabled:border-gray-300 disabled:cursor-not-allowed"
                                            x-on:click="$wire.addToCart('drink', {{ $drink->id }}, selectedSize)"
                                            @if (($drink->product->stock ?? 0) <= 0) disabled @endif>
                                            Add to Cart
                                        </button>
                                    </div>
                                @else
                                    {{-- TANPA SIZE --}}
                                    @php
                                        $price = $drink->product->price ?? ($drink->price ?? 0);
                                    @endphp

                                    <p class="text-lg font-bold text-gray-800 mt-2">
                                        Rp{{ number_format($price, 0, ',', '.') }}
                                    </p>

                                    <button
                                        class="font-bold mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition disabled:font-medium disabled:bg-white disabled:text-gray-500 disabled:border-gray-300 disabled:cursor-not-allowed"
                                        wire:click="addToCart('drink', {{ $drink->id }})"
                                        @if (($drink->product->stock ?? 0) <= 0) disabled @endif>
                                        Add to Cart
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
    <!-- End Product List Component -->

</div>
