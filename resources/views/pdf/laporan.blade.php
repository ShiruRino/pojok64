<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .sub-title {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 6px 5px;
        }
        table th {
            background: #f3f3f3;
        }
        .total-row td {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>
<body>

    <h2>LAPORAN KEUANGAN</h2>
    <div class="sub-title">
        Periode: {{ $start }} s/d {{ $end }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Order ID</th>
                <th>Bayar</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $t)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $t->created_at->format('d-m-Y H:i') }}</td>
                    <td class="text-center">{{ $t->order_id }}</td>
                    <td class="text-right">Rp{{ number_format($t->amount_paid, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($t->order->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="4" class="text-right">Total Pemasukan</td>
                <td class="text-right">
                    Rp{{ number_format($total, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>
