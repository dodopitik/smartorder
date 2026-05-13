<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pesanan Baru</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
    <h2 style="margin-bottom:4px;">📢 Pesanan Baru Masuk</h2>

    <p style="margin:0 0 8px 0; font-size:14px;">
        Order Code: <strong>{{ $order->order_code }}</strong><br>
        Status: <strong>{{ $order->status }}</strong><br>
        Metode Pembayaran: <strong>{{ strtoupper($order->payment_method) }}</strong><br>
        @if (!empty($order->table_number))
            Kamar: <strong>{{ $order->table_number }}</strong><br>
        @endif
        Waktu Pesan: <strong>{{ $order->created_at }}</strong>
    </p>

    @if (!empty($order->notes))
        <p style="font-size:13px; margin:0 0 12px 0;">
            Catatan Customer:
            <em>"{{ $order->notes }}"</em>
        </p>
    @endif

    <h3 style="font-size:15px; margin:16px 0 8px 0;">Item Dipesan:</h3>

    <table cellpadding="6" cellspacing="0" border="1" style="border-collapse: collapse; font-size:13px; min-width:300px;">
        <thead style="background:#f2f2f2;">
            <tr>
                <th align="left">Menu</th>
                <th align="center">Qty</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
          @php 
    $subtotal = 0; 
@endphp

@foreach ($orderItems as $item)
    @php
        $lineTotal = $item->total_price ?? 0;
        $subtotal += $item->price ?? 0;
    @endphp

    <tr>
        <td>{{ $item->item->name ?? 'Item tidak ditemukan' }}</td>
        <td align="center">{{ $item->quantity ?? 0 }}</td>
        <td align="right">
            Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}
        </td>
    </tr>
@endforeach
</tbody>
</table>

<p style="margin-top:16px; font-size:14px;">
    <strong>Subtotal:</strong>
    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
</p>

<p style="margin-top:16px; font-size:14px;">
    <strong>Tax (11%):</strong>
    Rp {{ number_format($order->tax, 0, ',', '.') }}
</p>

<p style="margin-top:16px; font-size:14px;">
    <strong>Total:</strong>
    Rp {{ number_format($order->grandtotal, 0, ',', '.') }}
</p>
    <p style="margin-top:24px; font-size:12px; color:#666;">
        Mohon segera diproses 🙏
    </p>
</body>
</html>
