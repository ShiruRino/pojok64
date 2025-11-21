<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 10px;
        }
        .receipt {
            width: 260px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .center {
            text-align: center;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        table {
            width: 100%;
            margin-top: 5px;
        }
        table th, table td {
            text-align: left;
            padding: 3px 0;
        }
        table td:nth-child(3) {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 12px;
        }
    </style>
</head>
<body>

<div class="receipt">

    <h2>RECEIPT</h2>
    <div class="center">Toko / Restoran Anda</div>
    <div class="line"></div>

    <p>
        <strong>No Transaksi:</strong> {{ $transaction->id }}<br>
        <strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
        <strong>Kasir:</strong> Kasir<br>
        <strong>Pelanggan:</strong> {{ $transaction->order->customer_name }}
    </p>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->order->detailOrders as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr class="total-row">
            <td>Total</td>
            <td></td>
            <td>Rp {{ number_format($transaction->order->total, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Dibayar</td>
            <td></td>
            <td>Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Kembalian</td>
            <td></td>
            <td>
                Rp {{ number_format($transaction->amount_paid - $transaction->order->total, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer">
        Terima kasih telah berkunjung!<br>
        ~~ Sampai jumpa lagi ~~
    </div>

</div>

</body>
</html>
