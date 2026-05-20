@extends('admin.layout.master')
@section('title', 'Global Setting')

@section('css')
    @include('platform.partials.styles')
@endsection

@section('content')
    <div class="page-heading">
        <h3>Global Setting</h3>
        <p class="text-muted mb-0">Atur nama platform, support contact, dan formula billing dari satu tempat.</p>
    </div>

    <div class="page-content">
        <section class="platform-card">
            <div class="card-body">
                <form method="POST" action="{{ route('platform.settings.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="platform-form-grid">
                        <div class="span-6">
                            <label class="form-label">Nama Platform</label>
                            <input type="text" name="platform_name" class="form-control" value="{{ old('platform_name', $settings['platform_name']) }}" required>
                        </div>
                        <div class="span-6">
                            <label class="form-label">Fee per Order</label>
                            <input type="number" min="0" name="monthly_fee_per_order" class="form-control" value="{{ old('monthly_fee_per_order', $settings['monthly_fee_per_order']) }}" required>
                            <small class="text-muted d-block mt-1">
                                Fee ini disimpan per transaksi pada saat order dibuat. Mengubah nilai di sini hanya berlaku untuk transaksi <strong>setelah</strong> perubahan disimpan; transaksi lama tetap memakai fee aslinya.
                            </small>
                        </div>
                        <div class="span-6">
                            <label class="form-label">Support Email</label>
                            <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $settings['support_email']) }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Support Phone</label>
                            <input type="text" name="support_phone" class="form-control" value="{{ old('support_phone', $settings['support_phone']) }}">
                        </div>
                        <div class="span-12">
                            <label class="form-label">Hero Message</label>
                            <textarea name="hero_message" class="form-control" rows="3">{{ old('hero_message', $settings['hero_message']) }}</textarea>
                        </div>
                        <div class="span-12">
                            <label class="form-label">Catatan Billing</label>
                            <textarea name="billing_cycle_note" class="form-control" rows="4">{{ old('billing_cycle_note', $settings['billing_cycle_note']) }}</textarea>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-4 mb-0">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Setting</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
