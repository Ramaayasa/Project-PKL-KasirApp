@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Daftar Servis</h2>
                <p class="text-muted mb-0">Kelola data servis pelanggan</p>
            </div>
            <a href="{{ route('servis.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Servis
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">ID</th>
                                <th>Kode Servis</th>
                                <th>Nama Pelanggan</th>
                                <th>No HP</th>
                                <th>Tipe Barang</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servis as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $item->kode_servis }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item->nama_pelanggan }}</strong>
                                    </td>
                                    <td>{{ $item->no_hp ?? '-' }}</td>
                                    <td>{{ $item->tipe_barang ?? '-' }}</td>
                                    <td>
                                        <small>{{ Str::limit($item->keluhan, 50) }}</small>
                                    </td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($item->status == 'proses')
                                            <span class="badge bg-info">Proses</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            {{-- Tombol WhatsApp --}}
                                            @if($item->no_hp)
                                                @php
                                                    // Normalisasi nomor HP
                                                    $phone = preg_replace('/\D/', '', $item->no_hp);
                                                    if (substr($phone, 0, 1) === '0') {
                                                        $phone = '62' . substr($phone, 1);
                                                    }
                                                    // Pesan WA yang akan dikirim
                                                    $message =
                                                        "Halo $item->nama_pelanggan, saya dari Ganesha Computer.\n\n" .
                                                        "Barang servis Anda:\n\n" .
                                                        "Kode Servis: $item->kode_servis\n" .
                                                        "Barang: $item->tipe_barang ($item->seri_barang)\n" .
                                                        "Status: " . ucfirst($item->status) . "\n\n" .
                                                        "Terima kasih telah mempercayakan servis Anda kepada kami.";

                                                    $message = urlencode($message);
                                                @endphp

                                                <a href="https://wa.me/{{ $phone }}?text={{ $message }}" target="_blank" class="btn btn-sm btn-success" title="Chat WA">
                                                    <i class="bi bi-whatsapp"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('servis.show', $item->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('servis.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('servis.destroy', $item->id) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin hapus data servis ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <strong>Belum ada data servis</strong>
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