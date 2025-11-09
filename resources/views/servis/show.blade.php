@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Detail Servis</h2>
                    <a href="{{ route('servis.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-tools"></i>
                            Kode Servis: {{ $servis->kode_servis }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Data Pelanggan -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-person-fill"></i> Data Pelanggan
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="150"><strong>Nama</strong></td>
                                        <td>: {{ $servis->nama_pelanggan }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. Telepon</strong></td>
                                        <td>: {{ $servis->no_telepon }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat</strong></td>
                                        <td>: {{ $servis->alamat ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Detail Barang -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-box-seam"></i> Detail Barang
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="150"><strong>Tipe Barang</strong></td>
                                        <td>: {{ $servis->tipe_barang }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Seri Barang</strong></td>
                                        <td>: {{ $servis->seri_barang }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Warna</strong></td>
                                        <td>: {{ $servis->warna_barang ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Password</strong></td>
                                        <td>: {{ $servis->password ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kelengkapan</strong></td>
                                        <td>: {{ $servis->kelengkapan ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Keluhan & Status -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-clipboard-check"></i> Keluhan & Status
                        </h6>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <strong>Keluhan:</strong>
                                    <p class="mt-2">{{ $servis->keluhan }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Status:</strong>
                                    @if($servis->status == 'pending')
                                        <span class="badge bg-warning text-dark ms-2">Pending</span>
                                    @elseif($servis->status == 'proses')
                                        <span class="badge bg-info ms-2">Proses</span>
                                    @else
                                        <span class="badge bg-success ms-2">Selesai</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('servis.edit', $servis->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('servis.destroy', $servis->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin hapus data servis ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection