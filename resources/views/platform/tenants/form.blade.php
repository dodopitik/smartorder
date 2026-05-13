@extends('admin.layout.master')
@section('title', $mode === 'create' ? 'Tambah Tenant' : 'Edit Tenant')

@section('css')
    @include('platform.partials.styles')
@endsection

@section('content')
    <div class="page-heading">
        <h3>{{ $mode === 'create' ? 'Tambah Tenant Baru' : 'Edit Tenant' }}</h3>
        <p class="text-muted mb-0">Kelola identitas tenant dan akun owner utamanya dalam satu form.</p>
    </div>

    <div class="page-content">
        <section class="platform-card">
            <div class="card-body">
                <form method="POST" action="{{ $mode === 'create' ? route('platform.tenants.store') : route('platform.tenants.update', $tenant) }}" enctype="multipart/form-data">
                    @csrf
                    @if ($mode === 'edit')
                        @method('PUT')
                    @endif

                    <div class="platform-form-grid">
                        <div class="span-12">
                            <h5 class="mb-0">Profil Tenant</h5>
                            <p class="text-muted">Informasi yang akan tampil di landing dan customer area tenant.</p>
                        </div>

                        <div class="span-6">
                            <label class="form-label">Nama Tenant</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $tenant->name) }}" required>
                        </div>
                        <div class="span-6">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $tenant->slug) }}" required>
                        </div>
                        <div class="span-6">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $tenant->tagline) }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $tenant->address) }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Email Kontak</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $tenant->contact_email) }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Nomor Kontak</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $tenant->contact_phone) }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Primary Color</label>
                            <input type="text" name="primary_color" class="form-control" value="{{ old('primary_color', $tenant->primary_color ?: '#ff7a18') }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Secondary Color</label>
                            <input type="text" name="secondary_color" class="form-control" value="{{ old('secondary_color', $tenant->secondary_color ?: '#111827') }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Hero Title</label>
                            <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $tenant->hero_title) }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Hero Subtitle</label>
                            <input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $tenant->hero_subtitle) }}">
                        </div>
                        <div class="span-12">
                            <label class="form-label">Logo Tenant</label>
                            @if ($tenant->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('img_tenant_logo/' . $tenant->logo) }}" alt="Logo" style="height: 48px; border-radius: 10px;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="form-text">Format: JPG, PNG, GIF. Maks 2MB. Kosongkan jika tidak ingin mengubah.</div>
                        </div>
                        <div class="span-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $tenant->description) }}</textarea>
                        </div>
                        <div class="span-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $tenant->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Tenant aktif dan tampil di landing page</label>
                            </div>
                        </div>

                        <div class="span-12 mt-3">
                            <h5 class="mb-0">Owner Tenant</h5>
                            <p class="text-muted">Akun ini akan masuk ke dashboard admin tenant untuk mengelola operasional outlet.</p>
                        </div>

                        <div class="span-6">
                            <label class="form-label">Nama Owner</label>
                            <input type="text" name="owner_fullname" class="form-control" value="{{ old('owner_fullname', $owner?->fullname) }}" required>
                        </div>
                        <div class="span-6">
                            <label class="form-label">Username Owner</label>
                            <input type="text" name="owner_username" class="form-control" value="{{ old('owner_username', $owner?->username) }}">
                        </div>
                        <div class="span-6">
                            <label class="form-label">Email Owner</label>
                            <input type="email" name="owner_email" class="form-control" value="{{ old('owner_email', $owner?->email) }}" required>
                        </div>
                        <div class="span-6">
                            <label class="form-label">Nomor Owner</label>
                            <input type="text" name="owner_phone" class="form-control" value="{{ old('owner_phone', $owner?->phone) }}" required>
                        </div>
                        <div class="span-12">
                            <label class="form-label">Password Owner {{ $mode === 'edit' ? '(kosongkan jika tidak diganti)' : '' }}</label>
                            <input type="password" name="owner_password" class="form-control" {{ $mode === 'create' ? 'required' : '' }}>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-4 mb-0">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">{{ $mode === 'create' ? 'Simpan Tenant' : 'Update Tenant' }}</button>
                        <a href="{{ route('platform.tenants.index') }}" class="btn btn-light">Kembali</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
