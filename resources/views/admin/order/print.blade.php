<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Struk {{ $order->order_code }}</title>
  <style>
    @page { size: 80mm auto; margin: 0; }
    * { font-family: 'Courier New', monospace; }
    body { width: 80mm; margin: 0; padding: 0; }
    .receipt { padding: 8px 10px; }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    .line { border-top: 1px dashed #000; margin: 6px 0; }
    .kv { display: flex; justify-content: space-between; font-size: 12px; }
    .small { font-size: 11px; }
    .title { font-size: 14px; margin: 0; }
    @media screen {
      .print-hint { background: #fff3cd; padding: 6px 10px; margin: 6px 10px; font-size: 12px; }
    }
    @media print {
      .no-print { display: none !important; }
    }
  </style>
</head>
<body onload="window.print(); setTimeout(()=>window.close(), 500);">
  <div class="receipt">
    <div class="center">
      <div class="bold title">{{ strtoupper($order->tenant->name ?? 'HAPPY FRIED') }}</div>
      <div class="small">{{ now()->format('d M Y H:i') }}</div>
      <div class="small">Kode: <span class="bold">{{ $order->order_code }}</span></div>
      <div class="small">Kamar: {{ $order->table_number }}</div>
    </div>

    <div class="line"></div>

    @foreach($orderItems as $it)
      @php
        $name = \Illuminate\Support\Str::limit($it->item->name, 22);
        $qty = $it->quantity;
        $lineTotal = $it->price;
        $unitPrice = $qty > 0 ? (int) round($lineTotal / $qty) : $lineTotal;
      @endphp
      <div class="small bold">{{ $name }}</div>
      <div class="kv small">
        <div>{{ $qty }} x Rp{{ number_format($unitPrice, 0, ',', '.') }}</div>
        <div>Rp{{ number_format($lineTotal, 0, ',', '.') }}</div>
      </div>
    @endforeach

    <div class="line"></div>

    <div class="kv small"><div>Sub Total</div><div>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</div></div>
    <div class="kv small"><div>Tax 11%</div><div>Rp{{ number_format($order->tax, 0, ',', '.') }}</div></div>
    <div class="kv small bold"><div>Total</div><div>Rp{{ number_format($order->grandtotal, 0, ',', '.') }}</div></div>

    <div class="line"></div>

    <div class="kv small">
      <div>Metode</div><div class="bold">{{ strtoupper($order->payment_method) }}</div>
    </div>
    <div class="kv small">
      <div>Status</div><div class="bold">{{ strtoupper($order->status) }}</div>
    </div>

    @if($order->notes)
      <div class="line"></div>
      <div class="small">Catatan: {{ \Illuminate\Support\Str::limit($order->notes, 80) }}</div>
    @endif

    <div class="line"></div>

    <div class="center small">Terima kasih - semoga pengalaman makan Anda menyenangkan.</div>

    <div class="no-print print-hint center small">Jendela ini akan otomatis print lalu menutup sendiri.</div>
  </div>
</body>
</html>
