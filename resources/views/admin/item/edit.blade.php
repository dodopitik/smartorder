@extends('admin.layout.master')

@section('title', 'Edit Menu')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Menu Tenant</h3>
                    <p class="text-subtitle text-muted">Perbarui detail menu agar tetap sesuai dengan operasional tenant.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('tenant-admin.panel', ['tenant' => $currentTenant->slug]) }}">Admin Panel</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('items.index', ['tenant' => $currentTenant->slug]) }}">Daftar Menu</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="itemForm" action="{{ route('items.update', ['tenant' => $currentTenant->slug, 'item' => $item->id]) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="image" class="form-label">Gambar Menu</label>
                            <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                            @if ($item->image)
                                <div class="mt-2">
                                    <p class="mb-1">Gambar saat ini:</p>
                                    <img src="{{ asset('img_item_upload/' . $item->image) }}" alt="{{ $item->name }}" style="max-height: 120px;">
                                </div>
                            @endif
                            <div class="mt-2" id="preview-container" style="display: none;">
                                <p class="mb-1">Preview gambar baru:</p>
                                <img id="preview-image" src="" style="max-height: 120px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Menu</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                value="{{ old('name', $item->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                                value="{{ old('price', $item->price) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category" name="category_id" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch mt-1">
                                <input type="hidden" name="is_available" value="0">
                                <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1"
                                    {{ old('is_available', $item->is_available) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">Tampilkan menu ini untuk customer</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#saveModal">Simpan</button>
                        <button type="button" class="btn btn-light-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Apakah Anda yakin ingin membatalkan perubahan ini? Semua data yang belum disimpan akan hilang.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <a href="{{ route('items.index', ['tenant' => $currentTenant->slug]) }}" class="btn btn-danger">Ya, Batalkan</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="saveModalLabel">Konfirmasi Simpan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Apakah Anda yakin ingin menyimpan perubahan menu ini?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmSave">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('confirmSave').addEventListener('click', function() {
            document.getElementById('itemForm').submit();
        });

        document.getElementById('image').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                previewImage.src = '';
            }
        });
    </script>
@endsection
