@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle"></i>
                            Tambah Kategori {{ ucfirst($tipe) }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('kategori.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipe" value="{{ $tipe }}">

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    Tipe Kategori <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe" id="tipeBarang"
                                            value="barang" {{ $tipe === 'barang' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tipeBarang">
                                            <i class="bi bi-box"></i> Barang
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe" id="tipeServis"
                                            value="servis" {{ $tipe === 'servis' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tipeServis">
                                            <i class="bi bi-tools"></i> Servis
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Nama Kategori <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}" placeholder="Contoh: Elektronik, Perbaikan LCD, dll" required
                                    autofocus>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kode kategori akan otomatis dibuat</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="3" placeholder="Deskripsi kategori (opsional)">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('kategori.index', ['tipe' => $tipe]) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection