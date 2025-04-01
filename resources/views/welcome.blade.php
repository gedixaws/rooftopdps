<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Rooftop Denpasar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body>
    <!-- Navbar Component -->
    <header>
        <div class="fixed top-0 left-0 w-full bg-white shadow-lg z-50 py-2 px-6 flex items-center justify-between">


            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="images/rooftop.png" alt="Logo" class="h-10">
                <h1 class="text-2xl font-bold text-black sm:text-2xl dark:text-black max-sm:text-sm">ROOFTOP DENPASAR
                </h1>
                <p class=" text-sm text-gray-500 dark:text-gray-400">
                </p>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Tombol Order Yuk -->
                <button
                    class="flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-black border border-black shadow-sm transition hover:bg-gray-100 focus:ring-1 max-sm:px-3 max-sm:py-2 max-sm:text-xs gap-2 ">
                    Menu
                </button>

                <!-- Cart Component -->
                <div x-data="{ open: false }">
                    <button @click="open = true"
                        class="flex items-center justify-center gap-2 bg-black text-white px-5 py-3 rounded-lg shadow-sm transition hover:bg-gray-900 max-sm:px-3 max-sm:py-2 max-sm:text-xs">
                        <!-- Icon Cart -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5 max-sm:w-4 max-sm:h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l3.6 7.59a2 2 0 0 0 1.74 1.16h7.52a2 2 0 0 0 1.74-1.16L21 6H5.21"></path>
                            <circle cx="10.5" cy="19.5" r="1.5"></circle>
                            <circle cx="17.5" cy="19.5" r="1.5"></circle>
                        </svg>

                        <!-- Text & Item Count -->
                        <span class="font-medium">Cart</span>
                        <span class="text-xs font-bold text-white">
                            @livewire('cart-counter')
                        </span>
                    </button>

                    <!-- Shopping Cart Panel -->
                    <div x-show="open" @click.away="open = false"
                        class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform transition-transform"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="translate-x-full">

                        <div class="p-4 border-b flex justify-between items-center">
                            <h2 class="text-lg font-bold">Shopping Cart</h2>
                            <button @click="open = false" class="text-gray-600">&times;</button>
                        </div>
                        @livewire('cart')
                    </div>
                </div>
            </div>

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
                Masukkan ID transaksi untuk melihat pesanan Anda.
            </p>
        </div>

        <div class="max-w-xl mx-auto mt-6">
            <form action="{{ route('check-transaction') }}" method="POST"
                class="flex flex-wrap items-center gap-4 sm:flex-nowrap">
                @csrf
                <input type="text" name="transaction_id" placeholder="Masukkan ID Transaksi (Contoh: #A123)"
                    class="flex-1 w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-700 shadow-sm transition focus:border-black focus:ring-2 focus:ring-gray-900 focus:outline-none"
                    required />

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
            </form>
        </div>
    </section>





    <div id="toast-top-right"
        class="fixed flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow-sm top-20 right-5 dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800 "
        style="display: none;" role="alert">
        <div class="flex">
            <div class="ms-3 text-sm font-normal">
                <div class="mb-2 text-sm font-normal">Hi Neil, thanks for sharing your thoughts regarding Flowbite.
                </div>
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white justify-center items-center shrink-0 text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                data-dismiss-target="#toast-top-right" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    </div>



    <!-- End Call to Action Component-->

    <!-- Product List Component -->

    @livewire('menu-web')

    <!-- End Product List Component -->

    <!-- Footer-->
    <button id="playButton">Play Notification</button>
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
    @livewireScripts
</body>
<script>
    // Create an audio object
    var audio = new Audio('../audio/notification.mp3');

    window.addEventListener('showAlert', event => {
        // Menampilkan pesan alert
        const alertElement = document.getElementById('toast-top-right');
        audio.play().catch(function(error) {
            console.log('Error playing audio:', error);
        });
        // Tampilkan alert
        alertElement.style.display = 'block';
    });
</script>

</html>
