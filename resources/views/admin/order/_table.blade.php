@foreach ($orders as $order)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            <div class="order-code">{{ $order['order_code'] }}</div>
            <div class="order-meta">{{ $order->created_at->format('d M Y H:i') }}</div>
        </td>
        <td>
            <div class="order-customer">
                <span class="order-customer-name">{{ $order->user->fullname }}</span>
                <span class="order-customer-meta">Order #{{ $order->id }}</span>
            </div>
        </td>
        <td>
            <span class="order-total">Rp {{ number_format($order['grandtotal'], 0, ',', '.') }}</span>
        </td>
        <td>
            <span class="orders-pill room">
                <i class="bi bi-door-open-fill"></i>
                Kamar {{ $order['table_number'] ?: '-' }}
            </span>
        </td>
        <td>
            <span class="orders-pill {{ $order['payment_method'] === 'qris' ? 'qris' : 'cash' }}">
                <i class="bi {{ $order['payment_method'] === 'qris' ? 'bi-qr-code-scan' : 'bi-cash-coin' }}"></i>
                {{ strtoupper($order['payment_method']) }}
            </span>
        </td>
        <td>
            <div class="order-notes">{{ $order['notes'] ?: 'Tidak ada catatan tambahan.' }}</div>
        </td>
        <td>
            <span class="orders-pill {{ $order['status'] }}">
                <i class="bi bi-record-circle-fill"></i>
                {{ ucfirst($order['status']) }}
            </span>
        </td>
        <td>
            <div class="orders-actions">
                <a href="{{ route('orders.show', ['tenant' => $currentTenant->slug, 'orderId' => $order->id]) }}"
                    class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-1"></i>Lihat
                </a>

                @if ($order->status === 'pending' && $order->payment_method === 'cash')
                    <button type="button"
                        class="btn btn-outline-success btn-sm order-status-action"
                        data-url="{{ route('orders.settlement', ['tenant' => $currentTenant->slug, 'orderId' => $order->id]) }}"
                        data-action-label="Settlement"
                        data-order-code="{{ $order->order_code }}"
                        data-message="Status pesanan akan diubah menjadi settlement dan pesanan siap diteruskan ke proses berikutnya.">
                        <i class="bi bi-check-circle me-1"></i>Settlement
                    </button>
                @endif

                @if ($order->status === 'settlement')
                    <button type="button"
                        class="btn btn-outline-info btn-sm order-status-action"
                        data-url="{{ route('orders.cooked', ['tenant' => $currentTenant->slug, 'orderId' => $order->id]) }}"
                        data-action-label="Cooked"
                        data-order-code="{{ $order->order_code }}"
                        data-message="Pesanan akan ditandai selesai dimasak agar tim hotel tahu order ini siap diantar.">
                        <i class="bi bi-fire me-1"></i>Cooked
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endforeach
