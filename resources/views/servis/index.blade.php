@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Servis</h1>
        <a href="{{ route('servis.create') }}" class="btn btn-primary mb-3">+ Tambah Servis</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pelanggan</th>
                    <th>No HP</th>
                    <th>Keluhan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servis as $s)
                    <tr>
                        <td>{{ $s->id }}</td>
                        <td>{{ $s->nama_pelanggan }}</td>
                        <td>{{ $s->no_hp }}</td>
                        <td>{{ $s->keluhan }}</td>
                        <td>
                            <a href="{{ route('servis.edit', $s->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('servis.destroy', $s->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data servis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
