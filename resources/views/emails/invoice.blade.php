<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f2 f2 f2;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                <h2>OnionTrade Pro</h2>
                <p>Invoice #: {{ $order->invoice_no }}</p>
                <p>Date: {{ $order->created_at->format('d M Y') }}</p>
            </div>
            <div style="text-align: right;">
                <h4>Billed To:</h4>
                <p>{{ $order->name }}<br>{{ $order->phone }}<br>{{ $order->address }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ number_format($item->unit_price, 2) }} TK</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total_price, 2) }} TK</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total Amount: {{ number_format($order->total_amount, 2) }} TK
        </div>

        <p style="margin-top: 50px; text-align: center; color: #777;">Thank you for shopping with OnionTrade Pro!</p>
    </div>
</body>

</html>