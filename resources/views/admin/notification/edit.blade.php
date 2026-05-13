@extends('admin.layout.master')

@section('title', 'Pengaturan Notifikasi')

@section('content')
<div class="notification-settings-page">
    <div class="notification-hero">
        <div class="notification-hero-icon">
            <i class="bi bi-bell-fill"></i>
        </div>
        <div>
            <h2 class="notification-title">Pengaturan Notifikasi</h2>
            <p class="notification-subtitle">Atur bagaimana tim kamu menerima notifikasi pesanan baru.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('notification.settings.update', ['tenant' => $tenant->slug]) }}">
        @csrf
        @method('PUT')

        {{-- Browser Notification Info --}}
        <div class="notification-card mb-4">
            <div class="notification-card-header">
                <div class="notification-card-icon bg-info-subtle text-info">
                    <i class="bi bi-browser-chrome"></i>
                </div>
                <div>
                    <h5 class="notification-card-title">Browser Notification & Sound</h5>
                    <p class="notification-card-desc">Notifikasi otomatis muncul di browser + bunyi alert saat ada pesanan baru. Aktif secara default untuk semua karyawan yang membuka admin panel.</p>
                </div>
            </div>
            <div class="notification-card-body">
                <div class="notification-status-badge active">
                    <i class="bi bi-check-circle-fill"></i>
                    Selalu aktif — tidak perlu konfigurasi
                </div>
                <p class="notification-note mt-2">
                    <i class="bi bi-info-circle"></i>
                    Pastikan karyawan mengizinkan notifikasi browser saat diminta. Sound alert akan berbunyi otomatis.
                </p>
            </div>
        </div>

        {{-- Email Notification --}}
        <div class="notification-card">
            <div class="notification-card-header">
                <div class="notification-card-icon bg-warning-subtle text-warning">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div>
                    <h5 class="notification-card-title">Email Notifikasi Pesanan</h5>
                    <p class="notification-card-desc">Kirim email ke satu atau beberapa alamat setiap kali ada pesanan baru masuk.</p>
                </div>
            </div>
            <div class="notification-card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="notifyToggle"
                        name="notify_on_new_order" value="1"
                        {{ $tenant->notify_on_new_order ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="notifyToggle">
                        Aktifkan email notifikasi pesanan baru
                    </label>
                </div>

                <div class="mb-3" id="emailFieldGroup">
                    <label for="notificationEmails" class="form-label fw-semibold">Alamat Email Penerima</label>
                    <textarea class="form-control" id="notificationEmails" name="notification_emails"
                        rows="3" placeholder="admin@outlet.com, chef@outlet.com">{{ implode(', ', $emails) }}</textarea>
                    <div class="form-text">Pisahkan beberapa email dengan koma. Email akan dikirim ke semua alamat di atas.</div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="bi bi-check2-circle me-1"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<style>
    .notification-settings-page {
        max-width: 720px;
    }

    .notification-hero {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .notification-hero-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f97316, #ea580c);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .notification-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }

    .notification-subtitle {
        font-size: 0.9rem;
        color: #6b7280;
        margin: 0;
    }

    .notification-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
    }

    .notification-card-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .notification-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .notification-card-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    .notification-card-desc {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 0;
    }

    .notification-card-body {
        padding: 1.25rem 1.5rem;
    }

    .notification-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .notification-status-badge.active {
        background: #dcfce7;
        color: #166534;
    }

    .notification-note {
        font-size: 0.82rem;
        color: #6b7280;
    }

    html[data-bs-theme="dark"] .notification-card {
        border-color: rgba(148, 163, 184, 0.15);
    }

    html[data-bs-theme="dark"] .notification-card-header {
        background: rgba(30, 41, 59, 0.5);
        border-color: rgba(148, 163, 184, 0.15);
    }

    html[data-bs-theme="dark"] .notification-card-desc,
    html[data-bs-theme="dark"] .notification-subtitle,
    html[data-bs-theme="dark"] .notification-note {
        color: #94a3b8;
    }

    html[data-bs-theme="dark"] .notification-status-badge.active {
        background: rgba(22, 101, 52, 0.2);
        color: #86efac;
    }
</style>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('notifyToggle');
        const emailGroup = document.getElementById('emailFieldGroup');

        function updateVisibility() {
            emailGroup.style.display = toggle.checked ? 'block' : 'none';
        }

        updateVisibility();
        toggle.addEventListener('change', updateVisibility);
    });
</script>
@endsection
