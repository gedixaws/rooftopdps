<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Rooftop Denpasar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

</head>

<style>
    [x-cloak] {
        display: none !important;
    }

    @media print {
    body * {
        visibility: hidden;
    }

    #print-area, #print-area * {
        visibility: visible;
    }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    /* Sembunyikan tombol cetak */
    button {
        display: none !important;
    }

    #print-area img {
        max-height: 60px;
    }
}
</style>

<body>
    <!-- Navbar Component -->
    <header>
        <div class="fixed top-0 left-0 w-full bg-white shadow-lg z-50 py-2 px-6 flex items-center justify-between">


            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img src="../images/rooftop.png" alt="Logo" class="h-10">
                    <div>
                        <h1 class="text-2xl font-bold text-black sm:text-2xl dark:text-black max-sm:text-sm">
                            ROOFTOP DENPASAR
                        </h1>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Tombol Order Yuk -->
                <a href=" {{ route('home') }}"
                    class="flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-black border border-black shadow-sm transition hover:bg-gray-100 focus:ring-1 max-sm:px-3 max-sm:py-2 max-sm:text-xs gap-2 ">
                    Menu
                </a>

                <!-- Cart Component -->
                <div x-data="{ open: false }" x-cloak>
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
        @if (session('error'))
            <div
                class="max-w-md mx-auto mt-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="h-6 w-6 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v6m0 4v.01M21 12A9 9 0 113 12a9 9 0 0118 0z"></path>
                    </svg>
                    <p class="flex-1">{{ session('error') }}</p>
                    <button onclick="this.parentElement.parentElement.style.display = 'none'"
                        class="text-red-700 hover:text-red-900 focus:outline-none ml-3">
                        ✖
                    </button>
                </div>
            </div>
        @endif

    </section>

    <div x-data="$store.modalStore" x-show="showModal" x-transition x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">

        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b">
                <div class="flex items-center space-x-2">
                    <!-- Icon Keranjang -->
                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m0-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Keranjang Kosong</h3>
                </div>
                <button @click="$store.modalStore.close()" class="text-gray-400 hover:text-gray-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-5 text-sm text-gray-700">
                Keranjang kosong, silakan tambahkan produk.
            </div>

            <!-- Footer -->
            <div class="flex justify-end px-5 py-4 border-t">
                <button @click="$store.modalStore.close()"
                    class="bg-black hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    OK
                </button>
            </div>
        </div>
    </div>

    <div x-data="$store.modalStore2" x-show="showModal2" x-transition x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">

        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b">
                <div class="flex items-center space-x-3">
                    <!-- Ikon user warning -->
                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m0-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Masukkan Nama</h3>
                </div>
                <button @click="$store.modalStore2.close()" class="text-gray-400 hover:text-gray-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-5 text-sm text-gray-700">
                Isi Nama yang benar untuk memudahkan kami dalam menghubungi Anda.
            </div>

            <!-- Footer -->
            <div class="flex justify-end px-5 py-4 border-t">
                <button @click="$store.modalStore2.close()"
                    class="bg-black hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    OK
                </button>
            </div>
        </div>
    </div>

    <div x-data="$store.modalStore3" x-show="showModal3" x-transition x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">

        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b">
                <div class="flex items-center space-x-3">
                    <!-- Ikon user warning -->
                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m0-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Stok Kosong</h3>
                </div>
                <button @click="$store.modalStore3.close()" class="text-gray-400 hover:text-gray-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-5 text-sm text-gray-700">
                <p class="text-gray-500" x-text="$store.modalStore3.message"></p>
            </div>

            <!-- Footer -->
            <div class="flex justify-end px-5 py-4 border-t">
                <button @click="$store.modalStore3.close()"
                    class="bg-black hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Transaction Component-->
    <div id="print-area" class="p-6">
        <div class="hidden print:flex items-center gap-3 mb-4">
            <img src="{{ asset('../images/rooftop.png') }}" alt="Logo" class="h-12">
            <h1 class="text-xl font-bold">ROOFTOP DENPASAR</h1>
        </div>
        <div class="mb-5 max-w-2xl mx-auto mt-10 px-4 py-6 bg-white shadow-lg rounded-lg">
            <h2 class="text-xl font-bold mb-4">Status Transaksi</h2>

            <p class="mb-2"><strong>ID Transaksi:</strong></p>
            <div
                class="p-4 bg-white border-l-8 border-black text-black font-extrabold rounded-lg shadow-md text-lg tracking-wide mb-6">
                {{ $order->transaction_id }}
            </div>
            <p class="mb-2"><strong>Nama:</strong> {{ Str::before($order->name, '-') }}</p>
            <p class="mb-2"><strong>Catatan:</strong> {{ $order->note ?? '-' }}</p>
            <p class="mb-2"><strong>Metode Pembayaran:</strong>
                {{ $order->paymentMethod->name ?? 'Tidak Diketahui' }}
            </p>
            <p class="mb-2"><strong>Dibayar:</strong> Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</p>
            <p class="mb-2"><strong>Kembalian:</strong> Rp {{ number_format($order->change_amount, 0, ',', '.') }}
            </p>
            <p class="mb-2"><strong>Waktu Order:</strong>
                {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y H:i') }}</p>
            <p class="mb-2"><strong>Status: </strong> {{ $order->status }}</p>

            <h3 class="font-semibold mt-4">Produk yang Dibeli</h3>
            <table class="w-full mt-2 border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">Produk</th>
                        <th class="p-2">Varian / Ukuran</th>
                        <th class="p-2">Qty</th>
                        <th class="p-2">Harga Satuan</th>
                        <th class="p-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderProducts as $orderProduct)
                        <tr class="border-b border-gray-300 text-center">
                            <td class="p-2">{{ $orderProduct->product->product_name }}</td>
                            <td class="p-2 text-center">
                                @if ($orderProduct->foodVariant)
                                    {{ $orderProduct->foodVariant->name }}
                                @elseif($orderProduct->drinkSize)
                                    {{ $orderProduct->drinkSize->size }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-2 text-center">{{ $orderProduct->quantity }}</td>
                            <td class="p-2 text-center">Rp {{ number_format($orderProduct->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="p-2 text-center">Rp
                                {{ number_format($orderProduct->quantity * $orderProduct->unit_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="mt-4 text-lg font-semibold">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

            <button onclick="window.print()" class="mt-4 bg-black text-white px-4 py-2 rounded">Cetak</button>
        </div>
    </div>

    <!-- End Transaction Component-->

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
    @livewireScripts
</body>

<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script>

    const notyfRight = new Notyf({
        duration: 3000,
        position: {
            x: 'right',
            y: 'top',
        },
        types: [{
                type: 'warning',
                background: 'orange',
                icon: {
                    className: 'material-icons',
                    tagName: 'i',
                    text: 'warning'
                }
            },
            {
                type: 'error',
                background: 'indianred',
                duration: 5000,
                dismissible: true
            }
        ]
    });

    const notyfLeft = new Notyf({
        duration: 3000,
        position: {
            x: 'left',
            y: 'top',
        },
        types: [{
                type: 'warning',
                background: 'orange',
                icon: {
                    className: 'material-icons',
                    tagName: 'i',
                    text: 'warning'
                }
            },
            {
                type: 'error',
                background: 'indianred',
                duration: 5000,
                dismissible: true
            }
        ]
    });

    window.addEventListener('showAlert_Added', event => {
        notyfRight.success('Produk Berhasil Ditambahkan!');

    });

    window.addEventListener('showAlert_stock', event => {
        notyfRight.error('Stok tidak mencukupi!');
    });

    window.addEventListener('showAlert_Remove', event => {
        notyfLeft.error('🗑️ Produk Telah Dihapus!');
    });

    document.addEventListener('alpine:init', () => {
        Alpine.store('modalStore', {
            showModal: false,
            open() {
                this.showModal = true;
            },
            close() {
                this.showModal = false;
            }
        });
    });
    document.addEventListener('alpine:init', () => {
        Alpine.store('modalStore2', {
            showModal2: false,
            open() {
                this.showModal2 = true;
            },
            close() {
                this.showModal2 = false;
            }
        });
    });

    document.addEventListener('alpine:init', () => {
        Alpine.store('modalStore3', {
            showModal3: false,
            open(message = '') {
                this.message = message;
                this.showModal3 = true;
            },
            close() {
                this.showModal3 = false;
                this.message = '';
            }
        });
    });

    window.addEventListener('showAlert_keranjang_kosong', () => {
        Alpine.store('modalStore').open();
    });

    window.addEventListener('showAlert_keranjang_kosong2', () => {
        Alpine.store('modalStore2').open();
    });

    window.addEventListener('showAlert_stok_kurang', () => {
        Alpine.store('modalStore3').open(event.detail.message);
    });
</script>

</html>
