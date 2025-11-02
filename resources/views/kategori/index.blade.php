@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Master Kategori</h2>
                <p class="text-muted mb-0">Kelola kategori {{ ucfirst($tipe) }}</p>
            </div>
            <a href="{{ route('kategori.create', ['tipe' => $tipe]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>

        <!-- Tab Kategori -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ $tipe === 'barang' ? 'active' : '' }}"
                    href="{{ route('kategori.index', ['tipe' => 'barang']) }}">
                    <i class="bi bi-box"></i> Kategori Barang
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tipe === 'servis' ? 'active' : '' }}"
                    href="{{ route('kategori.index', ['tipe' => 'servis']) }}">
                    <i class="bi bi-tools"></i> Kategori Servis
                </a>
            </li>
        </ul>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Kode</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Tipe</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-info">{{ $item->kode }}</span></td>
                                    <td class="fw-semibold">{{ $item->nama }}</td>
                                    <td>{{ $item->deskripsi ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $item->tipe === 'barang' ? 'bg-primary' : 'bg-success' }}">
                                            {{ ucfirst($item->tipe) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('kategori.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin hapus kategori ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Belum ada kategori {{ $tipe }}.
                                        <a href="{{ route('kategori.create', ['tipe' => $tipe]) }}">
                                            Tambah kategori baru
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection