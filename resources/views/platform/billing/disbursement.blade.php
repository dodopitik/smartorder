@extends('admin.layout.master')
@section('title', 'Penyetoran QRIS')

@section('css')
    @include('platform.partials.styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .disb-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .disb-badge.pending { background: rgba(245,158,11,0.12); color: #b45309; }
        .disb-badge.disbursed { background: rgba(22,163,74,0.12); color: #166534; }
        .disb-order-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 6px;
            font-size: 0.88rem;
        }
        .disb-order-table thead th {
            color: var(--admin-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 0.7rem 0.4rem;
        }
        .disb-order-table tbody td {
            padding: 0.7rem;
            background: var(--admin-surface-strong);
            border-top: 1px solid var(--admin-border);
            border-bottom: 1px solid var(--admin-border);
        }
        .disb-order-table tbody td:first-child {
            border-left: 1px solid var(--admin-border);
            border-radius: 10px 0 0 10px;
        }
        .disb-order-table tbody td:last-child {
            border-right: 1px solid var(--admin-border);
            border-radius: 0 10px 10px 0;
        }
        .disb-total-row {
            padding: 1rem;
            border-radius: 14px;
            background: rgba(22, 163, 74, 0.06);
            border: 1px solid rgba(22, 163, 74, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .disb-section-toggle {
            cursor: pointer;
            user-select: none;
        }
        .disb-section-toggle:hover {
            opacity: 0.8;
        }
    </style>
@endsection

@section('content')
    <div class="platform-shell">
        <div class="platform-hero">
            <div>
                <span class="badge bg-dark-subtle text-dark text-uppercase mb-3">Disbursement</span>
                <h2 class="mb-2">Penyetoran QRIS ke Tenant</h2>
                <p class="text-muted mb-0">List semua order QRIS yang sudah settlement. Tandai "Sudah Disetor" setelah kamu transfer ke tenant. Order baru yang masuk otomatis muncul sebagai "Belum Disetor".</p>
            </div>
        </div>

        {{-- KPI --}}
        <div class="platform-kpis">
            <div class="platform-kpi">
                <div class="label">Order Belum Disetor</div>
                <p class="value" style="color: #d97706;">{{ $totalPendingOrders }}</p>
            </div>
            <div class="platform-kpi">
                <div class="label">Total Harus Disetor</div>
                <p class="value" style="color: #16a34a;">Rp {{ number_format($grandPendingTotal, 0, ',', '.') }}</p>
            </div>
            <div class="platform-kpi">
                <div class="label">Fee per Order</div>
                <p class="value">Rp {{ number_format($feePerOrder, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Per Tenant --}}
        @if ($tenants->isEmpty())
            <div class="platform-card" style="padding: 1.25rem;">
                <div class="platform-empty">Belum ada order QRIS settlement.</div>
            </div>
        @else
            @foreach ($tenants as $tenantData)
                <div class="platform-card">
                    <div class="card-head d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>
                            <h4 class="mb-0">{{ $tenantData->tenant->name }}</h4>
                            <p class="text-muted mb-0 small">/{{ $tenantData->tenant->slug }}</p>
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- BELUM DISETOR --}}
                        @if ($tenantData->pending_orders->count() > 0)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><span class="disb-badge pending">● Belum Disetor</span> <span class="ms-2 text-muted small">{{ $tenantData->pending_orders->count() }} order</span></h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="disb-order-table">
                                        <thead>
                                            <tr>
                                                <th>Kode Order</th>
                                                <th>Tanggal</th>
                                                <th>Pelanggan</th>
                                                <th>Kamar</th>
                                                <th class="text-end">Grand Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tenantData->pending_orders as $order)
                                                <tr>
                                                    <td><strong>{{ $order->order_code }}</strong></td>
                                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $order->user?->fullname ?? '-' }}</td>
                                                    <td>{{ $order->table_number ?: '-' }}</td>
                                                    <td class="text-end"><strong>Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</strong></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="disb-total-row mt-3">
                                    <div>
                                        <div class="small text-muted">Revenue: Rp {{ number_format($tenantData->pending_total, 0, ',', '.') }} — Fee: Rp {{ number_format($tenantData->pending_fee, 0, ',', '.') }}</div>
                                        <strong class="fs-5" style="color: #16a34a;">Setor: Rp {{ number_format($tenantData->pending_to_disburse, 0, ',', '.') }}</strong>
                                    </div>
                                    <form action="{{ route('platform.disbursement.mark') }}" method="POST" class="js-mark-disbursed"
                                        data-tenant="{{ $tenantData->tenant->name }}"
                                        data-amount="Rp {{ number_format($tenantData->pending_to_disburse, 0, ',', '.') }}"
                                        data-count="{{ $tenantData->pending_orders->count() }}">
                                        @csrf
                                        <input type="hidden" name="tenant_id" value="{{ $tenantData->tenant->id }}">
                                        <button type="submit" class="btn btn-success">Tandai Semua Sudah Disetor</button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- SUDAH DISETOR --}}
                        @if ($tenantData->disbursed_orders->count() > 0)
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 disb-section-toggle" onclick="this.closest('.card-body').querySelector('.disb-done-list').classList.toggle('d-none')">
                                        <span class="disb-badge disbursed">✓ Sudah Disetor</span>
                                        <span class="ms-2 text-muted small">{{ $tenantData->disbursed_orders->count() }} order — Rp {{ number_format($tenantData->disbursed_total, 0, ',', '.') }}</span>
                                        <i class="bi bi-chevron-down ms-1 small"></i>
                                    </h6>
                                    @if ($tenantData->latest_batch_id)
                                        <form action="{{ route('platform.disbursement.undo') }}" method="POST" class="d-inline js-undo-disbursed"
                                            data-tenant="{{ $tenantData->tenant->name }}"
                                            data-count="{{ $tenantData->latest_batch_count }}"
                                            data-amount="Rp {{ number_format($tenantData->latest_batch_total, 0, ',', '.') }}"
                                            data-when="{{ $tenantData->latest_batch_at ? \Carbon\Carbon::parse($tenantData->latest_batch_at)->format('d/m/Y H:i') : '-' }}">
                                            @csrf
                                            <input type="hidden" name="tenant_id" value="{{ $tenantData->tenant->id }}">
                                            <button type="submit" class="btn btn-outline-secondary btn-sm" title="Batalkan batch penyetoran terakhir">
                                                Reset Batch Terakhir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="disb-done-list d-none">
                                    <div class="table-responsive">
                                        <table class="disb-order-table">
                                            <thead>
                                                <tr>
                                                    <th>Kode Order</th>
                                                    <th>Tanggal</th>
                                                    <th>Pelanggan</th>
                                                    <th>Kamar</th>
                                                    <th class="text-end">Grand Total</th>
                                                    <th>Disetor</th>
                                                    <th>Batch</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tenantData->disbursed_orders as $order)
                                                    <tr style="opacity: 0.6;">
                                                        <td>{{ $order->order_code }}</td>
                                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>{{ $order->user?->fullname ?? '-' }}</td>
                                                        <td>{{ $order->table_number ?: '-' }}</td>
                                                        <td class="text-end">Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</td>
                                                        <td class="small text-muted">{{ $order->disbursed_at ? \Carbon\Carbon::parse($order->disbursed_at)->format('d/m/Y') : '-' }}</td>
                                                        <td class="small text-muted">
                                                            @if ($order->disbursement_batch_id)
                                                                <code style="font-size: 0.7rem;">{{ \Illuminate\Support\Str::substr($order->disbursement_batch_id, -6) }}</code>
                                                                @if ($order->disbursement_batch_id === $tenantData->latest_batch_id)
                                                                    <span class="badge bg-warning-subtle text-warning ms-1" style="font-size:0.65rem;">terakhir</span>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($tenantData->pending_orders->count() === 0 && $tenantData->disbursed_orders->count() > 0)
                            <div class="text-center text-muted py-2">
                                <i class="bi bi-check-circle-fill text-success me-1"></i> Semua order sudah disetor.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- GRAND TOTAL --}}
            <div class="platform-card" style="border: 2px solid var(--admin-accent);">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-1">Total Belum Disetor (Semua Tenant)</h5>
                            <p class="text-muted mb-0 small">{{ $totalPendingOrders }} order dari {{ $tenants->filter(fn($t) => $t->pending_orders->count() > 0)->count() }} tenant</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <strong class="fs-3" style="color: #16a34a;">Rp {{ number_format($grandPendingTotal, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-mark-disbursed').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var tenant = form.dataset.tenant;
                    var amount = form.dataset.amount;
                    var count = form.dataset.count;

                    Swal.fire({
                        title: 'Tandai sudah disetor?',
                        html: '<div class="text-start"><p>Tenant: <b>' + tenant + '</b></p><p>Jumlah order: <b>' + count + '</b></p><p>Total setor: <b>' + amount + '</b></p><p class="text-muted small">Pastikan kamu sudah transfer ke rekening tenant.</p></div>',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, sudah disetor',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#16a34a',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    });
                });
            });

            document.querySelectorAll('.js-undo-disbursed').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var tenant = form.dataset.tenant;
                    var count = form.dataset.count;
                    var amount = form.dataset.amount;
                    var when = form.dataset.when;

                    Swal.fire({
                        title: 'Batalkan batch terakhir?',
                        html: '<div class="text-start"><p>Tenant: <b>' + tenant + '</b></p><p>Batch yang dibatalkan: <b>' + count + ' order</b></p><p>Total: <b>' + amount + '</b></p><p>Disetor pada: <b>' + when + '</b></p><p class="text-muted small">Hanya batch terakhir yang akan dikembalikan ke status belum disetor. Batch sebelumnya tetap aman.</p></div>',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, batalkan batch ini',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    });
                });
            });
        });
    </script>
@endsection
