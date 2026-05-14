<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.4;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            background: #fff;
        }

        /* 🎯 dompdf ফিক্স: ফ্লেক্সবক্সের বদলে স্ট্যান্ডার্ড টেবিল লেআউট */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: top;
            border: none !important;
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
            background-color: #f2f2f2;
            font-semibold: bold;
        }

        .total {
            text-align: right;
            margin-top: 25px;
            font-weight: bold;
            font-size: 1.2em;
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td>
                    <h2 style="margin: 0 0 5px 0; color: #007bff; font-size: 26px;">OnionTrade Pro</h2>
                    <p style="margin: 2px 0; color: #555;"><strong>Invoice #:</strong> {{ $order->invoice_number }}</p>
                    <p style="margin: 2px 0; color: #555;">
                        <strong>Date:</strong>
                        {{ $order->created_at ? date('d M Y', strtotime($order->created_at)) : date('d M Y') }}
                    </p>
                </td>
                <td style="text-align: right;">
                    <h4
                        style="margin: 0 0 5px 0; color: #333; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                        Billed To:</h4>
                    <p style="margin: 0; color: #555; font-size: 14px;">
                        <strong>{{ $order->name }}</strong><br>
                        {{ $order->phone }}<br>
                        <span style="text-transform: capitalize;">{{ $order->address }}</span>
                    </p>
                </td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th style="text-align: center;">Price</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>

                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td style="text-align: center;">{{ number_format($item->unit_price, 2) }} TK</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($item->total_price, 2) }} TK</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total Amount: {{ number_format($order->total_amount, 2) }} TK
        </div>

        <p
            style="margin-top: 60px; text-align: center; color: #777; font-size: 12px; border-top: 1px dashed #ddd; padding-top: 15px;">
            Thank you for shopping with Commercia Pro!
        </p>
    </div>
</body>

</html>