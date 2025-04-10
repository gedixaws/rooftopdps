<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        /* Layout untuk Thermal Printer */
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: white;
            width: 58mm;
            /* Lebar kertas 58mm */
        }

        .container {
            width: 100%;
            padding: 5px;
        }

        h1,
        p {
            text-align: center;
            margin: 0;
            padding: 2px 0;
        }

        h1 {
            font-size: 14px;
            font-weight: bold;
        }

        .info {
            text-align: center;
            border-bottom: 1px dashed black;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .table-header,
        .table-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            border-bottom: 1px dashed black;
        }

        .table-header {
            font-weight: bold;
        }

        .item-name {
            flex: 2;
        }

        .item-qty {
            flex: 0.5;
            text-align: center;
            min-width: 20px;
        }

        .item-price,
        .item-total {
            flex: 1;
            text-align: right;
        }

        .total {
            font-weight: bold;
            text-align: right;
            border-top: 1px dashed black;
            padding-top: 5px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 5px;
            border-top: 1px dashed black;
            padding-top: 5px;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
    </style>
</head>

<body>

    <div class="container">
        <h1>Rooftop Denpasar</h1>
        <p>Pemogan, Denpasar Selatan, Denpasar City, Bali 80119</p>
        <p>Instagram: @rooftop.denpasar</p>
        <p>WiFi: Rooftop | Pass: NakKodya</p>

        <div class="info">
            <p style="font-size: 13px; font-weight: bold; text-transform: uppercase; margin-bottom: 3px;">
                === ID Transaksi: {{ $order->transaction_id }} ===
            </p>
            <p>Tanggal: {{ $order->created_at->format('d F Y H:i') }}</p>
            <p>Pelanggan: {{ Str::before($order->name, '-') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Varian</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderProducts as $orderProduct)
                    <tr>
                        <td>{{ $orderProduct->product->product_name }}</td>
                        <td>
                            @if ($orderProduct->foodVariant)
                                {{ $orderProduct->foodVariant->name }}
                            @elseif($orderProduct->drinkSize)
                                {{ $orderProduct->drinkSize->size }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $orderProduct->quantity }}</td>
                        <td>Rp {{ number_format($orderProduct->unit_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($orderProduct->quantity * $orderProduct->unit_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
        </div>
    </div>

</body>

</html>
