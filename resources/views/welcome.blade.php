<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>Rooftop Denpasar</title>
</head>

<body>

    <!-- Navbar Component -->
    <header class="fixed top-0 left-0 w-full bg-white shadow-lg z-50 py-2 px-6 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <img src="images/rooftop.png" alt="Logo" class="h-10">
            <h1 class="text-2xl font-bold text-black sm:text-2xl dark:text-black max-sm:text-sm">ROOFTOP DENPASAR</h1>
            <p class=" text-sm text-gray-500 dark:text-gray-400">
            </p>
        </div>

        <div class="flex items-center gap-4">
            <button
                class="inline-block rounded-lg bg-white px-5 py-3 text-sm font-medium text-black transition focus:ring-1 focus:outline-hidden border border-black max-sm:px-2 max-sm:py-1 max-sm:w-auto"
                type="button">
                Order Yuk
            </button>

            <!-- Cart Component -->
            <div x-data="{ open: false }">
                <button @click="open = true"
                    class="inline-block rounded-lg bg-white px-5 py-3 text-sm font-medium text-black transition focus:outline-hidden "
                    type="button">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </button>


                <div class="relative z-10" x-show="open" @keydown.window.escape="open = false"
                    aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
                    <!-- Background backdrop -->
                    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" x-show="open" x-transition.opacity
                        @click="open = false" aria-hidden="true"></div>

                    <div class="fixed inset-0 overflow-hidden">
                        <div class="absolute inset-0 overflow-hidden">
                            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                                <!-- Panel -->
                                <div class="pointer-events-auto w-screen max-w-md transform transition ease-in-out duration-500 sm:duration-500"
                                    x-show="open" x-transition:enter="translate-x-full"
                                    x-transition:enter-end="translate-x-0" x-transition:leave="translate-x-0"
                                    x-transition:leave-end="translate-x-full">
                                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                                        <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                            <div class="flex items-start justify-between">
                                                <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">
                                                    Shopping
                                                    cart</h2>
                                                <div class="ml-3 flex h-7 items-center">
                                                    <button type="button"
                                                        class="relative -m-2 p-2 text-gray-400 hover:text-gray-500"
                                                        @click="open = false">
                                                        <span class="sr-only">Close panel</span>
                                                        <svg class="size-6" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18 18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- List Produk -->
                                            <div class="mt-8">
                                                <div class="flow-root">
                                                    <ul role="list" class="-my-6 divide-y divide-gray-200">
                                                        <li class="flex py-6">
                                                            <div
                                                                class="size-24 shrink-0 overflow-hidden rounded-md border border-gray-200">
                                                                <img src="https://tailwindui.com/plus-assets/img/ecommerce-images/shopping-cart-page-04-product-01.jpg"
                                                                    class="size-full object-cover">
                                                            </div>
                                                            <div class="ml-4 flex flex-1 flex-col">
                                                                <div>
                                                                    <div
                                                                        class="flex justify-between text-base font-medium text-gray-900">
                                                                        <h3>Throwback Hip
                                                                            Bag</a></h3>
                                                                        <p class="ml-4">$90.00</p>
                                                                    </div>
                                                                    <p class="mt-1 text-sm text-gray-500">Salmon
                                                                    </p>
                                                                </div>
                                                                <div
                                                                    class="flex flex-1 items-end justify-between text-sm">
                                                                    <div x-data="{ qty: 1 }"
                                                                        class="flex items-center space-x-2">
                                                                        <button @click="qty > 1 ? qty-- : qty"
                                                                            class="p-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                                                                            <svg class="size-5" fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke="currentColor" stroke-width="2">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M20 12H4" />
                                                                            </svg>
                                                                        </button>
                                                                        <input type="text" x-model="qty"
                                                                            class="w-10 text-center border border-gray-300 rounded-md focus:outline-none"
                                                                            readonly>
                                                                        <button @click="qty++"
                                                                            class="p-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                                                                            <svg class="size-5" fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke="currentColor" stroke-width="2">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M12 4v16m8-8H4" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                    <button type="button"
                                                                        class="font-medium text-indigo-600 hover:text-indigo-500">Remove</button>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Checkout -->
                                        <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <p>Subtotal</p>
                                                <p>$262.00</p>
                                            </div>
                                            <div class="mt-6">
                                                <a href="#"
                                                    class="flex items-center justify-center rounded-md bg-indigo-600 px-6 py-3 text-base font-medium text-white hover:bg-indigo-700">Checkout</a>
                                            </div>
                                            <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                                                <button type="button"
                                                    class="font-medium text-indigo-600 hover:text-indigo-500"
                                                    @click="open = false">Continue Shopping →</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </header>
    <!-- End Navbar Component -->
    <!-- End Cart Component -->

    <!-- Call to Action Component-->

    <section class="bg-gray-50">
        <div class="p-8 md:p-12 lg:px-16 lg:py-24 max-sm:mt-24">
            <div class="mx-auto max-w-lg text-center">
                <h2 class="text-2xl font-bold text-gray-900 md:text-3xl">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit
                </h2>

                <p class="hidden text-gray-500 sm:mt-4 sm:block">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quae dolor officia blanditiis
                    repellat in, vero, aperiam porro ipsum laboriosam consequuntur exercitationem incidunt
                    tempora nisi?
                </p>
            </div>

            <div class="mx-auto mt-8 max-w-xl">
                <form action="#" class="sm:flex sm:gap-4">
                    <div class="sm:flex-1">

                        <input type="Text" placeholder="#1234..."
                            class="w-full rounded-md border-gray-200 bg-white p-3 text-gray-700 shadow-xs transition focus:border-white focus:ring-3 focus:ring-yellow-400 focus:outline-hidden" />
                    </div>

                    <button type="submit"
                        class="group mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-black transition focus:ring-1 focus:ring-black focus:outline-hidden sm:mt-0 sm:w-auto border border-black">
                        <span class="text-sm font-bold"> Cek Transaksi </span>

                        <svg class="size-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- End Call to Action Component-->

    <!-- Product List Component -->
    <div class="text-center p-10">
        <h1 class="font-bold text-4xl mb-4">Menu Rooftop Denpasar</h1>
    </div>

    <div class="text-center p-10">
        <h1 class="text-3xl">Kopi</h1>
    </div>


    <section id="Projects" class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
        <!-- Product Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-xs mx-auto">
            <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                alt="Product" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-gray-500 text-xs uppercase">Kopi</span>
                <h2 class="text-lg font-semibold">Kopi Susu</h2>
                <p class="text-sm text-gray-600">Kopi yang berisi susu dan gula</p>

                <!-- Responsif Pilihan Hot / Cold -->
                <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                    <p class="text-lg font-bold text-black">Rp10.000</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="hot" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Hot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="cold" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Cold</span>
                        </label>
                    </div>
                </div>

                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>

        <!-- Product Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-xs mx-auto">
            <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                alt="Product" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-gray-500 text-xs uppercase">Kopi</span>
                <h2 class="text-lg font-semibold">Kopi Susu</h2>
                <p class="text-sm text-gray-600">Kopi yang berisi susu dan gula</p>

                <!-- Responsif Pilihan Hot / Cold -->
                <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                    <p class="text-lg font-bold text-black">Rp10.000</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="hot" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Hot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="cold" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Cold</span>
                        </label>
                    </div>
                </div>

                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>

        <!-- Product Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-xs mx-auto">
            <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                alt="Product" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-gray-500 text-xs uppercase">Kopi</span>
                <h2 class="text-lg font-semibold">Kopi Susu</h2>
                <p class="text-sm text-gray-600">Kopi yang berisi susu dan gula</p>

                <!-- Responsif Pilihan Hot / Cold -->
                <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                    <p class="text-lg font-bold text-black">Rp10.000</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="hot" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Hot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="cold" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Cold</span>
                        </label>
                    </div>
                </div>

                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>

        <!-- Product Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-xs mx-auto">
            <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                alt="Product" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-gray-500 text-xs uppercase">Kopi</span>
                <h2 class="text-lg font-semibold">Kopi Susu</h2>
                <p class="text-sm text-gray-600">Kopi yang berisi susu dan gula</p>

                <!-- Responsif Pilihan Hot / Cold -->
                <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                    <p class="text-lg font-bold text-black">Rp10.000</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="hot" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Hot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="cold" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Cold</span>
                        </label>
                    </div>
                </div>

                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>

        <!-- Product Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-xs mx-auto">
            <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                alt="Product" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-gray-500 text-xs uppercase">Kopi</span>
                <h2 class="text-lg font-semibold">Kopi Susu</h2>
                <p class="text-sm text-gray-600">Kopi yang berisi susu dan gula</p>

                <!-- Responsif Pilihan Hot / Cold -->
                <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                    <p class="text-lg font-bold text-black">Rp10.000</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="hot" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Hot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="cold" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Cold</span>
                        </label>
                    </div>
                </div>

                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>

        <!-- Product Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-xs mx-auto">
            <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                alt="Product" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-gray-500 text-xs uppercase">Kopi</span>
                <h2 class="text-lg font-semibold">Kopi Susu</h2>
                <p class="text-sm text-gray-600">Kopi yang berisi susu dan gula</p>

                <!-- Responsif Pilihan Hot / Cold -->
                <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                    <p class="text-lg font-bold text-black">Rp10.000</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="hot" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Hot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="cold" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Cold</span>
                        </label>
                    </div>
                </div>

                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>

        <!-- Product Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-xs mx-auto">
            <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                alt="Product" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-gray-500 text-xs uppercase">Kopi</span>
                <h2 class="text-lg font-semibold">Kopi Susu</h2>
                <p class="text-sm text-gray-600">Kopi yang berisi susu dan gula</p>

                <!-- Responsif Pilihan Hot / Cold -->
                <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                    <p class="text-lg font-bold text-black">Rp10.000</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="hot" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Hot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="cold" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Cold</span>
                        </label>
                    </div>
                </div>

                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>

        <!-- Product Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-xs mx-auto">
            <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                alt="Product" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-gray-500 text-xs uppercase">Kopi</span>
                <h2 class="text-lg font-semibold">Kopi Susu</h2>
                <p class="text-sm text-gray-600">Kopi yang berisi susu dan gula</p>

                <!-- Responsif Pilihan Hot / Cold -->
                <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                    <p class="text-lg font-bold text-black">Rp10.000</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="hot" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Hot</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="serving_type" value="cold" class="w-4 h-4 text-blue-600">
                            <span class="ml-1 text-xs">Cold</span>
                        </label>
                    </div>
                </div>

                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Add to Cart
                </button>
            </div>
        </div>

    </section>
    <!-- End Product List Component -->

    <!-- Footer-->
    <footer class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex justify-center sm:justify-start text-white font-bold text-lg">
                    ROOFTOP DENPASAR
                </div>

                <p class="mt-4 text-center text-sm text-gray-500 lg:mt-0 lg:text-right dark:text-gray-400">
                    Copyright &copy; 2025. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    <!-- End Footer-->
</body>

</html>
