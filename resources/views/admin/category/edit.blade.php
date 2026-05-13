@extends('admin.layout.master')

@section('title', 'Edit Kategori')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Kategori Menu</h3>
                <p class="text-subtitle text-muted">Perbarui nama dan detail kategori untuk tenant aktif.</p>
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
                    <form class="form" action="{{ route('categories.update', ['tenant' => $currentTenant->slug, 'category' => $categories->id]) }}" id="categoryForm"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row ">
                                <div class=" ">
                                    <div class="form-group">
                                        <label for="category_name">Nama Kategori</label>
                                        <input type="text"
                                            class="form-control @error('category_name') is-invalid @enderror"
                                            id="category_name" placeholder="" name="category_name" required
                                            value="{{ old('category_name', $categories->category_name) }}">
                                        @error('category_name')
                                            <div class="invalid-feedback"> {{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary me-1 mb-1" data-bs-toggle="modal"
                                            data-bs-target="#saveModal">
                                            Simpan
                                        </button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        <button type="button" class="btn btn-light-danger me-1 mb-1" data-bs-toggle="modal"
                                            data-bs-target="#cancelModal">
                                            Batal
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi Simpan -->
    <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="saveModalLabel">Konfirmasi Simpan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menyimpan perubahan kategori ini?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="categoryForm">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Batal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin membatalkan perubahan ini? Semua data yang belum disimpan akan hilang.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <a href="{{ route('categories.index', ['tenant' => $currentTenant->slug]) }}" class="btn btn-danger">Ya, Batalkan</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('confirmSave').addEventListener('click', function() {
            document.querySelector('form').submit();
        });
    </script>
@endsection
