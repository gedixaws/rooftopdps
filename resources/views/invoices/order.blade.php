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
            <p>Invoice #{{ $order->id }}</p>
            <p>Tanggal: {{ $order->created_at->format('d M Y') }}</p>
            <p>Pelanggan: {{ $order->name }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Note</th>
                    <th>Jenis</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderProducts as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->order->note }}</td>
                        <td>{{ $item->serving_type }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</td>
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
