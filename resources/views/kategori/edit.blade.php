@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil"></i>
                            Edit Kategori {{ ucfirst($kategori->tipe) }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kode Kategori</label>
                                <input type="text" class="form-control" value="{{ $kategori->kode }}" disabled>
                                <small class="text-muted">Kode tidak bisa diubah</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipe</label>
                                <input type="text" class="form-control" value="{{ ucfirst($kategori->tipe) }}" disabled>
                                <small class="text-muted">Tipe tidak bisa diubah</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Nama Kategori <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $kategori->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="3">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('kategori.index', ['tipe' => $kategori->tipe]) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-save"></i> Update Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection