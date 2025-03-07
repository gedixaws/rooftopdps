<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        /* Reset dan Umum */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        p {
            font-size: 14px;
            color: #555;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .info {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }

        .print-button {
            display: block;
            width: 100%;
            background: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            cursor: pointer;
        }

        .print-button:hover {
            background: #0056b3;
        }

        /* Mode Cetak */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                border: none;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>Invoice #{{ $order->id }}</h1>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
                <p><strong>Pelanggan:</strong> {{ $order->customer_name }}</p>
            </div>
            <div class="info">
                <p><strong>Nama Toko</strong></p>
                <p>Jl. Contoh No. 123, Kota</p>
                <p>Instagram: <a href="https://instagram.com/toko" target="_blank">@toko</a></p>
                <p>WiFi: <strong>NamaWiFi</strong> | Password: <strong>12345678</strong></p>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderProducts as $item)
                <tr>
                    <td>{{ $item->product->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total -->
        <p class="total">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

        <!-- Tombol Cetak -->
        <button class="print-button" onclick="window.print()">Cetak Invoice</button>
    </div>

</body>

</html>