@extends('admin.layout.master')
@section('title', 'Kelola Pesanan')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .orders-shell {
            display: grid;
            gap: 1.2rem;
        }

        .orders-hero,
        .orders-card,
        .orders-kpi {
            border-radius: 26px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .orders-hero {
            padding: 1.6rem;
            background:
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.14), transparent 24%),
                linear-gradient(135deg, rgba(239, 246, 255, 0.92) 0%, rgba(255, 255, 255, 0.95) 52%, rgba(255, 247, 237, 0.92) 100%);
        }

        .orders-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.06);
            color: #334155;
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 800;
        }

        .orders-title {
            margin: .9rem 0 .35rem;
            font-size: clamp(1.8rem, 3vw, 2.3rem);
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .orders-copy {
            color: #64748b;
            line-height: 1.7;
            margin: 0;
            max-width: 760px;
        }

        .orders-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .orders-kpi {
            position: relative;
            overflow: hidden;
            padding: 1.15rem;
        }

        .orders-kpi::after {
            content: "";
            position: absolute;
            top: -26px;
            right: -26px;
            width: 110px;
            height: 110px;
            border-radius: 999px;
            background: var(--orders-glow, rgba(59, 130, 246, 0.12));
        }

        .orders-kpi-label {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .88rem;
            margin-bottom: .35rem;
        }

        .orders-kpi-value {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(1.4rem, 2vw, 1.9rem);
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .orders-kpi-note {
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: .82rem;
            margin: .35rem 0 0;
        }

        .orders-card {
            padding: 1.2rem;
        }

        .orders-card-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .orders-card-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .orders-card-copy {
            margin: .25rem 0 0;
            color: #64748b;
            line-height: 1.65;
            font-size: .9rem;
        }

        .orders-table-wrap {
            overflow: auto;
        }

        .orders-table {
            width: 100%;
            min-width: 980px;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .orders-table thead th {
            color: #64748b;
            font-size: .79rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 0;
            padding: 0 .95rem .2rem;
            white-space: nowrap;
        }

        .orders-table tbody tr {
            background: rgba(15, 23, 42, 0.04);
        }

        .orders-table tbody td {
            padding: 1rem .95rem;
            border-top: 1px solid rgba(148, 163, 184, 0.12);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            vertical-align: middle;
        }

        .orders-table tbody td:first-child {
            border-left: 1px solid rgba(148, 163, 184, 0.12);
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
        }

        .orders-table tbody td:last-child {
            border-right: 1px solid rgba(148, 163, 184, 0.12);
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        .order-code {
            font-weight: 900;
            letter-spacing: -.02em;
        }

        .order-meta {
            color: #64748b;
            font-size: .82rem;
            margin-top: .2rem;
        }

        .order-customer {
            min-width: 180px;
        }

        .order-customer-name {
            font-weight: 800;
            display: block;
        }

        .order-customer-meta {
            color: #64748b;
            font-size: .82rem;
        }

        .order-total {
            font-weight: 900;
            white-space: nowrap;
        }

        .orders-pill {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
            padding: .38rem .7rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .orders-pill.room {
            background: rgba(15, 23, 42, 0.08);
            color: #334155;
        }

        .orders-pill.cash {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .orders-pill.qris {
            background: rgba(59, 130, 246, 0.14);
            color: #1d4ed8;
        }

        .orders-pill.pending {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .orders-pill.settlement {
            background: rgba(22, 163, 74, 0.14);
            color: #15803d;
        }

        .orders-pill.cooked {
            background: rgba(59, 130, 246, 0.14);
            color: #1d4ed8;
        }

        .order-notes {
            max-width: 220px;
            color: #64748b;
            font-size: .85rem;
            line-height: 1.55;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .orders-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
            justify-content: flex-end;
        }

        .orders-actions .btn {
            border-radius: 999px;
        }

        html[data-bs-theme="dark"] .orders-hero,
        html[data-bs-theme="dark"] .orders-card,
        html[data-bs-theme="dark"] .orders-kpi {
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.96) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 20px 42px rgba(2, 6, 23, 0.34);
        }

        html[data-bs-theme="dark"] .orders-hero {
            background:
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), transparent 24%),
                linear-gradient(135deg, rgba(30, 41, 59, 0.96) 0%, rgba(17, 24, 39, 0.98) 52%, rgba(15, 23, 42, 0.98) 100%);
        }

        html[data-bs-theme="dark"] .orders-eyebrow,
        html[data-bs-theme="dark"] .orders-pill.room {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
        }

        html[data-bs-theme="dark"] .orders-title,
        html[data-bs-theme="dark"] .orders-kpi-value,
        html[data-bs-theme="dark"] .orders-card-title,
        html[data-bs-theme="dark"] .order-code,
        html[data-bs-theme="dark"] .order-customer-name,
        html[data-bs-theme="dark"] .order-total {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .orders-copy,
        html[data-bs-theme="dark"] .orders-kpi-label,
        html[data-bs-theme="dark"] .orders-kpi-note,
        html[data-bs-theme="dark"] .orders-card-copy,
        html[data-bs-theme="dark"] .order-meta,
        html[data-bs-theme="dark"] .order-customer-meta,
        html[data-bs-theme="dark"] .order-notes,
        html[data-bs-theme="dark"] .orders-table thead th {
            color: #94a3b8;
        }

        html[data-bs-theme="dark"] .orders-table tbody tr {
            background: rgba(148, 163, 184, 0.08);
        }

        html[data-bs-theme="dark"] .orders-table tbody td {
            border-color: rgba(148, 163, 184, 0.14);
        }

        @media (max-width: 768px) {
            .orders-card-head {
                align-items: stretch;
            }

            .orders-actions {
                justify-content: flex-start;
            }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
@endsection

@section('content')
    @php
        $lastOrderId = $orders->max('id') ?? 0;
        $pendingOrders = $orders->where('status', 'pending')->count();
        $settlementOrders = $orders->where('status', 'settlement')->count();
        $cookedOrders = $orders->where('status', 'cooked')->count();
        $cashOrders = $orders->where('payment_method', 'cash')->count();
        $qrisOrders = $orders->where('payment_method', 'qris')->count();
        $grossRevenue = (int) $orders->sum('grandtotal');
    @endphp

    <div class="orders-shell">
        <section class="orders-hero">
            <span class="orders-eyebrow"><i class="bi bi-bag-check-fill"></i> Order Operations</span>
            <h1 class="orders-title">Kelola Pesanan Tenant</h1>
            <p class="orders-copy">
                Pantau semua transaksi tenant dari satu meja kerja yang lebih bersih. Tim bisa melihat pelanggan,
                kamar, metode pembayaran, status operasional, lalu meneruskan order ke tahap berikutnya dengan cepat.
            </p>
        </section>

        <section class="orders-kpi-grid">
            <article class="orders-kpi" style="--orders-glow: rgba(249, 115, 22, 0.12);">
                <div class="orders-kpi-label">Total Pesanan</div>
                <p class="orders-kpi-value" id="kpi-total">{{ number_format($orders->count(), 0, ',', '.') }}</p>
                <p class="orders-kpi-note">Seluruh order tenant yang sedang tampil di daftar.</p>
            </article>
            <article class="orders-kpi" style="--orders-glow: rgba(245, 158, 11, 0.12);">
                <div class="orders-kpi-label">Pesanan Pending</div>
                <p class="orders-kpi-value" id="kpi-pending">{{ number_format($pendingOrders, 0, ',', '.') }}</p>
                <p class="orders-kpi-note">Perlu pembayaran atau tindak lanjut berikutnya.</p>
            </article>
            <article class="orders-kpi" style="--orders-glow: rgba(34, 197, 94, 0.12);">
                <div class="orders-kpi-label">Settlement</div>
                <p class="orders-kpi-value" id="kpi-settlement">{{ number_format($settlementOrders, 0, ',', '.') }}</p>
                <p class="orders-kpi-note">Pembayaran sudah diterima dan siap diteruskan.</p>
            </article>
            <article class="orders-kpi" style="--orders-glow: rgba(59, 130, 246, 0.12);">
                <div class="orders-kpi-label">Cooked</div>
                <p class="orders-kpi-value" id="kpi-cooked">{{ number_format($cookedOrders, 0, ',', '.') }}</p>
                <p class="orders-kpi-note">Pesanan sudah selesai dimasak oleh kitchen.</p>
            </article>
            <article class="orders-kpi" style="--orders-glow: rgba(168, 85, 247, 0.12);">
                <div class="orders-kpi-label">Order Cash</div>
                <p class="orders-kpi-value">{{ number_format($cashOrders, 0, ',', '.') }}</p>
                <p class="orders-kpi-note">Pembayaran diselesaikan lewat kasir.</p>
            </article>
            <article class="orders-kpi" style="--orders-glow: rgba(14, 165, 233, 0.12);">
                <div class="orders-kpi-label">Order QRIS</div>
                <p class="orders-kpi-value">{{ number_format($qrisOrders, 0, ',', '.') }}</p>
                <p class="orders-kpi-note">Pembayaran digital tenant selama periode tampil.</p>
            </article>
            <article class="orders-kpi" style="--orders-glow: rgba(16, 185, 129, 0.12);">
                <div class="orders-kpi-label">Gross Revenue</div>
                <p class="orders-kpi-value" id="kpi-revenue">Rp {{ number_format($grossRevenue, 0, ',', '.') }}</p>
                <p class="orders-kpi-note">Akumulasi nilai transaksi dari semua order ini.</p>
            </article>
        </section>

        <section class="orders-card">
            <div class="orders-card-head">
                <div>
                    <h2 class="orders-card-title">Daftar Pesanan Masuk</h2>
                    <p class="orders-card-copy">Lihat detail order, buka halaman transaksi, dan dorong status operasional langsung dari tabel ini.</p>
                </div>
            </div>

            <div class="orders-table-wrap" id="orders-table-container">
                <table class="orders-table table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Kamar</th>
                            <th>Pembayaran</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                        @include('admin.order._table', ['orders' => $orders, 'currentTenant' => $currentTenant])
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Disable global polling on this page (handled locally)
    window.__ORDER_PAGE_ACTIVE = true;

    document.addEventListener('DOMContentLoaded', function() {
        var ORDERS_URL = "{{ route('orders.index', ['tenant' => $currentTenant->slug]) }}";
        var CHECK_URL = "{{ route('orders.checkNew', ['tenant' => $currentTenant->slug]) }}";
        var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var lastOrderId = {{ $lastOrderId ?? 0 }};
        var lastStatus = @json($lastOrderStatus ?? '');
        var audioCtx = null;

        function fmt(n) { return new Intl.NumberFormat('id-ID').format(n); }

        // Sound via Web Audio API
        function playSound() {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                var t = audioCtx.currentTime;
                function beep(freq, start, dur) {
                    var o = audioCtx.createOscillator();
                    var g = audioCtx.createGain();
                    o.connect(g); g.connect(audioCtx.destination);
                    o.type = 'sine'; o.frequency.value = freq;
                    g.gain.setValueAtTime(0.3, t + start);
                    g.gain.exponentialRampToValueAtTime(0.01, t + start + dur);
                    o.start(t + start); o.stop(t + start + dur);
                }
                beep(880, 0, 0.25);
                beep(1174, 0.15, 0.25);
                beep(1318, 0.3, 0.3);
            } catch(e) { console.warn('Sound error:', e); }
        }

        // Unlock audio on first click
        document.addEventListener('click', function unlock() {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
            document.removeEventListener('click', unlock);
        }, { once: true });

        // In-app toast notification
        function showToast(msg) {
            var c = document.getElementById('order-toast-container');
            if (!c) {
                c = document.createElement('div');
                c.id = 'order-toast-container';
                c.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:10px;';
                document.body.appendChild(c);
            }
            var t = document.createElement('div');
            t.style.cssText = 'background:#16a34a;color:#fff;padding:14px 20px;border-radius:12px;font-size:0.9rem;font-weight:600;box-shadow:0 8px 30px rgba(0,0,0,0.2);display:flex;align-items:center;gap:10px;animation:slideIn 0.3s ease;cursor:pointer;max-width:360px;';
            t.innerHTML = '<span style="font-size:1.3rem">🔔</span><span>' + msg + '</span>';
            t.onclick = function() { t.remove(); };
            c.appendChild(t);
            setTimeout(function() {
                t.style.opacity = '0'; t.style.transform = 'translateX(100%)'; t.style.transition = 'all 0.3s ease';
                setTimeout(function() { t.remove(); }, 300);
            }, 6000);
        }

        // Refresh tabel via AJAX
        function refreshTable() {
            fetch(ORDERS_URL, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                credentials: 'same-origin'
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var tbody = document.getElementById('orders-table-body');
                if (tbody && data.html) {
                    tbody.innerHTML = data.html;
                    bindActionButtons();
                }
                if (data.totalOrders !== undefined) { var el = document.getElementById('kpi-total'); if (el) el.textContent = fmt(data.totalOrders); }
                if (data.pendingOrders !== undefined) { var el = document.getElementById('kpi-pending'); if (el) el.textContent = fmt(data.pendingOrders); }
                if (data.settlementOrders !== undefined) { var el = document.getElementById('kpi-settlement'); if (el) el.textContent = fmt(data.settlementOrders); }
                if (data.cookedOrders !== undefined) { var el = document.getElementById('kpi-cooked'); if (el) el.textContent = fmt(data.cookedOrders); }
                if (data.grossRevenue !== undefined) { var el = document.getElementById('kpi-revenue'); if (el) el.textContent = 'Rp ' + fmt(data.grossRevenue); }
                if (data.lastOrderId) lastOrderId = data.lastOrderId;
                if (data.lastOrderStatus) lastStatus = data.lastOrderStatus;
            })
            .catch(function(err) { console.error('Refresh error:', err); });
        }

        // Polling
        function checkNewOrders() {
            fetch(CHECK_URL + '?last_id=' + lastOrderId + '&last_status=' + encodeURIComponent(lastStatus), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                credentials: 'same-origin'
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.has_new || data.status_changed) {
                    if (data.has_new) {
                        playSound();
                        showToast('Pesanan baru masuk! Tabel sudah diperbarui.');
                    }
                    refreshTable();
                }
                if (data.latest_id) lastOrderId = data.latest_id;
                if (data.latest_status !== undefined) lastStatus = data.latest_status;
            })
            .catch(function() {});
        }

        setInterval(checkNewOrders, 5000);

        // Action buttons via AJAX
        function bindActionButtons() {
            document.querySelectorAll('.order-status-action').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var actionUrl = btn.getAttribute('data-url');
                    var actionLabel = btn.getAttribute('data-action-label') || 'Update';
                    var orderCode = btn.getAttribute('data-order-code') || '';
                    var message = btn.getAttribute('data-message') || '';

                    Swal.fire({
                        title: actionLabel + ' Pesanan?',
                        html: '<div class="text-start"><div class="fw-semibold mb-2">' + orderCode + '</div><div class="text-muted">' + message + '</div></div>',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, ' + actionLabel,
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: { confirmButton: 'btn btn-primary mx-2', cancelButton: 'btn btn-light mx-2' },
                        buttonsStyling: false,
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            fetch(actionUrl, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': CSRF_TOKEN,
                                    'Content-Type': 'application/json'
                                },
                                credentials: 'same-origin'
                            })
                            .then(function(r) { return r.json(); })
                            .then(function(data) {
                                if (data.success) {
                                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, showConfirmButton: false });
                                    refreshTable();
                                }
                            })
                            .catch(function() { Swal.fire('Error', 'Gagal memperbarui status.', 'error'); });
                        }
                    });
                });
            });
        }

        bindActionButtons();
    });
    </script>
@endsection
