@extends('admin.layout.master')
@section('title', 'Billing Platform')

@section('css')
    @include('platform.partials.styles')
@endsection

@section('content')
    <div class="page-heading">
        <h3>Billing Platform</h3>
        <p class="text-muted mb-0">Estimasi fee tenant bulan ini berdasarkan order yang masuk.</p>
    </div>

    <div class="page-content">
        <section class="platform-shell">
            <div class="platform-kpis">
                <div class="platform-kpi">
                    <div class="label">Fee per Order</div>
                    <p class="value">Rp {{ number_format($feePerOrder, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Total Order Bulan Ini</div>
                    <p class="value">{{ number_format($orderCountTotal, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Gross Revenue Bulan Ini</div>
                    <p class="value">Rp {{ number_format($grossRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Total Fee Platform</div>
                    <p class="value">Rp {{ number_format($platformFeeTotal, 0, ',', '.') }}</p>
                </div>
                <div class="platform-kpi">
                    <div class="label">Catatan Siklus</div>
                    <p class="mb-0 text-muted">{{ $billingNote ?: 'Belum ada catatan billing.' }}</p>
                </div>
            </div>

            <div class="platform-card">
                <div class="card-body table-responsive">
                    <table class="platform-table table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Tenant</th>
                                <th>Order Bulan Ini</th>
                                <th>Gross Revenue</th>
                                <th>Fee Platform</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($billings as $billing)
                                <tr>
                                    <td>
                                        <strong>{{ $billing->name }}</strong>
                                        <div class="text-muted small">/{{ $billing->slug }}</div>
                                    </td>
                                    <td>{{ number_format($billing->order_count, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format((int) $billing->gross_revenue, 0, ',', '.') }}</td>
                                    <td><strong>Rp {{ number_format((int) $billing->platform_fee, 0, ',', '.') }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="platform-empty">Belum ada data billing tenant.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
