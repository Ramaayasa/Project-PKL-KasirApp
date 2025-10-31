@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Tambah Servis Baru</h2>
                        <p class="text-muted mb-0">Input data pelanggan dan detail barang servis</p>
                    </div>
                    <a href="{{ route('servis.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <!-- Form Card -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('servis.store') }}" method="POST">
                            @csrf

                            <!-- Section: Data Pelanggan -->
                            <div class="mb-4">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-person-fill"></i> Data Pelanggan
                                </h5>
                                <div class="row g-3">
                                    <!-- Nama Pelanggan -->
                                    <div class="col-md-6">
                                        <label for="nama_pelanggan" class="form-label fw-semibold">
                                            Nama Pelanggan <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('nama_pelanggan') is-invalid @enderror"
                                            id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                                            placeholder="Contoh: Budi Santoso" required>
                                        @error('nama_pelanggan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- No. Telepon -->
                                    <div class="col-md-6">
                                        <label for="no_telepon" class="form-label fw-semibold">
                                            No. Telepon <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                            id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                                            placeholder="Contoh: 081234567890" required>
                                        @error('no_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Alamat -->
                                    <div class="col-12">
                                        <label for="alamat" class="form-label fw-semibold">
                                            Alamat
                                        </label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                            name="alamat" rows="2"
                                            placeholder="Alamat lengkap pelanggan">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Section: Detail Barang -->
                            <div class="mb-4">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-box-seam"></i> Detail Barang
                                </h5>
                                <div class="row g-3">
                                    <!-- Tipe Barang -->
                                    <div class="col-md-6">
                                        <label for="tipe_barang" class="form-label fw-semibold">
                                            Tipe Barang <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('tipe_barang') is-invalid @enderror"
                                            id="tipe_barang" name="tipe_barang" value="{{ old('tipe_barang') }}"
                                            placeholder="Contoh: Laptop, HP, TV" required>
                                        @error('tipe_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Seri Barang -->
                                    <div class="col-md-6">
                                        <label for="seri_barang" class="form-label fw-semibold">
                                            Seri Barang <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('seri_barang') is-invalid @enderror"
                                            id="seri_barang" name="seri_barang" value="{{ old('seri_barang') }}"
                                            placeholder="Contoh: Asus ROG Strix G15" required>
                                        @error('seri_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Section: Keluhan & Servis -->
                            <div class="mb-4">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-tools"></i> Keluhan & Detail Servis
                                </h5>
                                <div class="row g-3">
                                    <!-- Keluhan -->
                                    <div class="col-12">
                                        <label for="keluhan" class="form-label fw-semibold">
                                            Keluhan <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('keluhan') is-invalid @enderror" id="keluhan"
                                            name="keluhan" rows="3"
                                            placeholder="Jelaskan keluhan atau masalah yang dialami..."
                                            required>{{ old('keluhan') }}</textarea>
                                        @error('keluhan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="col-12">
                                        <label for="keterangan" class="form-label fw-semibold">
                                            Keterangan Tambahan
                                        </label>
                                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                            id="keterangan" name="keterangan" rows="2"
                                            placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Deskripsi Perbaikan -->
                                    <div class="col-12">
                                        <label for="deskripsi" class="form-label fw-semibold">
                                            Deskripsi Perbaikan
                                        </label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                            id="deskripsi" name="deskripsi" rows="2"
                                            placeholder="Diisi setelah proses servis (opsional)">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Biaya & Status -->
                                    <div class="col-md-6">
                                        <label for="biaya" class="form-label fw-semibold">
                                            Estimasi Biaya (Rp)
                                        </label>
                                        <input type="number" class="form-control @error('biaya') is-invalid @enderror"
                                            id="biaya" name="biaya" value="{{ old('biaya') }}" placeholder="0" min="0"
                                            step="1000">
                                        @error('biaya')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="status" class="form-label fw-semibold">
                                            Status Servis
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status">
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>
                                                Proses
                                            </option>
                                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>
                                                Selesai
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('servis.index') }}" class="btn btn-secondary px-4">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save"></i> Simpan Data Servis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-label.fw-semibold {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .card {
            border: none;
            border-radius: 10px;
        }

        hr {
            border-top: 2px solid #e9ecef;
        }

        .text-danger {
            font-weight: bold;
        }
    </style>
@endsection