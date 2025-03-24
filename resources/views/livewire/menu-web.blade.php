<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Rooftop Denpasar</title>
    @livewireStyles
</head>

<body>

    <div>
        <!-- Navbar Component -->
        <header class="fixed top-0 left-0 w-full bg-white shadow-lg z-50 py-2 px-6 flex items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="images/rooftop.png" alt="Logo" class="h-10">
                <h1 class="text-2xl font-bold text-black sm:text-2xl dark:text-black max-sm:text-sm">ROOFTOP DENPASAR
                </h1>
                <p class=" text-sm text-gray-500 dark:text-gray-400">
                </p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Tombol Order Yuk -->
                <button
                    class="inline-block rounded-lg bg-white px-5 py-3 text-sm font-medium text-black transition focus:ring-1 focus:outline-hidden border border-black max-sm:px-2 max-sm:py-1 max-sm:w-auto max-sm:text-xs"
                    type="button">
                    Order Yuk
                </button>

                <!-- Cart Component -->
                <livewire:cart />

            </div>


        </header>
        <!-- End Navbar Component -->
        <!-- End Cart Component -->

        <!-- Call to Action Component-->
        <section class="bg-gray-50 py-20 mt-10 px-4">
            <div class="max-w-lg mx-auto text-center">
                <h2 class="text-3xl font-bold text-gray-900">
                    Cek Status Transaksi
                </h2>
                <p class="text-gray-500 mt-2">
                    Masukkan ID transaksi untuk melihat status pesanan Anda.
                </p>
            </div>

            <div class="max-w-xl mx-auto mt-6">
                <form action="#" class="flex flex-wrap items-center gap-4 sm:flex-nowrap">
                    <input type="text" placeholder="Masukkan ID Transaksi (Contoh: #A123)"
                        class="flex-1 w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-700 shadow-sm transition focus:border-black focus:ring-2 focus:ring-gray-900 focus:outline-none" />

                    <button type="submit"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-lg bg-black px-5 py-3 text-white text-sm font-semibold transition hover:bg-gray-800">
                        Cek Transaksi
                        <svg class="size-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>
                </form>
            </div>
        </section>


        <!-- End Call to Action Component-->

        <!-- Product List Component -->

        <div class="text-center mt-10">
            <h2 class="text-4xl font-bold text-gray-800 underline decoration-gray-400 decoration-2">Our Menu</h2>
            <p class="text-gray-500 mt-1">Food & Drinks</p>
        </div>


        <div class="container mx-auto px-6 md:px-12 mt-10">
            @foreach ($categories as $category)
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">{{ $category->name }}</h2>

                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-4">
                        {{-- Makanan --}}
                        @foreach ($category->foods as $food)
                            @if ($food->variants->count())
                                {{-- Jika memiliki varian, tampilkan setiap varian sebagai card terpisah --}}
                                @foreach ($food->variants as $variant)
                                    <div class="bg-white shadow-md rounded-xl overflow-hidden border">
                                        <div class="relative p-3">
                                            <img src="{{ $food->product->image_url ?? 'default.jpg' }}"
                                                alt="{{ $variant->name }}" class="w-full h-32 object-contain">
                                        </div>
                                        <div class="p-3 text-center">
                                            <p class="text-gray-500 text-xs">Stock: {{ $food->product->stock ?? 0 }}</p>
                                            <h2 class="text-sm font-semibold mt-1">{{ $variant->name }}</h2>
                                            <p class="text-lg font-bold text-gray-800 mt-2">
                                                Rp{{ number_format($variant->price, 0, ',', '.') }}
                                            </p>
                                            <button
                                                wire:click="addToCart({{ $food->product->id }}, {{ $variant->id }}, null)"
                                                class="mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition">
                                                Add {{ $variant->name }}
                                            </button>
                                        </div>
                                @endforeach
                            @else
                                {{-- Jika tidak memiliki varian, tampilkan produk utama --}}
                                <div class="bg-white shadow-md rounded-xl overflow-hidden border">
                                    <div class="relative p-3">
                                        <img src="{{ $food->product->image_url ?? 'default.jpg' }}"
                                            alt="{{ $food->name }}" class="w-full h-32 object-contain">
                                    </div>
                                    <div class="p-3 text-center">
                                        <p class="text-gray-500 text-xs">Stock: {{ $food->product->stock ?? 0 }}</p>
                                        <h2 class="text-sm font-semibold mt-1">{{ $food->name }}</h2>
                                        <p class="text-lg font-bold text-gray-800 mt-2">
                                            Rp{{ number_format($food->price, 0, ',', '.') }}
                                        </p>
                                        <button type="button" wire:click="addToCart({{ $food->product->id }})"
                                            class="mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition">
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
                                                alt="{{ $size->size }}" class="w-full h-32 object-contain">
                                        </div>
                                        <div class="p-3 text-center">
                                            <p class="text-gray-500 text-xs">Stock: {{ $drink->product->stock ?? 0 }}
                                            </p>
                                            <h2 class="text-sm font-semibold mt-1">{{ $drink->name }} -
                                                {{ $size->size }}</h2>
                                            <p class="text-lg font-bold text-gray-800 mt-2">
                                                Rp{{ number_format($size->price, 0, ',', '.') }}
                                            </p>
                                            <button type="button"
                                                wire:click="addToCart({{ $drink->product->id }}, null, {{ $size->id }})"
                                                class="mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition">
                                                Add {{ $size->size }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                {{-- Jika tidak memiliki ukuran, tampilkan produk utama --}}
                                <div class="bg-white shadow-md rounded-xl overflow-hidden border">
                                    <div class="relative p-3">
                                        <img src="{{ $drink->product->image_url ?? 'default.jpg' }}"
                                            alt="{{ $drink->name }}" class="w-full h-32 object-contain">
                                    </div>
                                    <div class="p-3 text-center">
                                        <p class="text-gray-500 text-xs">Stock: {{ $drink->product->stock ?? 0 }}</p>
                                        <h2 class="text-sm font-semibold mt-1">{{ $drink->name }}</h2>
                                        <p class="text-lg font-bold text-gray-800 mt-2">
                                            Rp{{ number_format($drink->price, 0, ',', '.') }}
                                        </p>
                                        <button wire:click="addToCart({{ $drink->product->id }}, null, null)"
                                            class="mt-2 w-full border border-gray-800 text-gray-800 text-sm py-1 rounded-lg hover:bg-gray-800 hover:text-white transition">
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

        <!-- Footer-->
        <footer class="bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="flex justify-center sm:justify-start text-gray-900 font-bold text-lg dark:text-white">
                        ROOFTOP DENPASAR
                    </div>

                    <p class="mt-4 text-center text-sm text-gray-500 lg:mt-0 lg:text-right dark:text-gray-400">
                        Copyright &copy; 2025. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
        <!-- End Footer-->
    </div>
    @livewireScripts
</body>
