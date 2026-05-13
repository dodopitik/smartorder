@extends('admin.layout.master')
@section('title', 'Edit Owner')

@section('css')
    @include('platform.partials.styles')
@endsection

@section('content')
    <div class="page-heading">
        <h3>Edit Owner</h3>
        <p class="text-muted mb-0">Ubah data admin tenant: {{ $user->fullname }}</p>
    </div>

    <div class="page-content">
        <section class="platform-card" style="max-width: 640px;">
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('platform.owners.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="fullname" class="form-control" value="{{ old('fullname', $user->fullname) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tenant</label>
                        <select name="tenant_id" class="form-select" required>
                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ (int) old('tenant_id', $user->tenant_id) === $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }} (/{{ $tenant->slug }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password Baru <span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('platform.owners.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
