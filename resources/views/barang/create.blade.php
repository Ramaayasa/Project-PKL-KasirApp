@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Barang Baru</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('barang.store') }}">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="kode_barang">Kode Barang</label>
                            <input type="text" class="form-control" name="kode_barang" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="kategori">Kategori</label>
                            <input type="text" class="form-control" name="kategori" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="harga_beli">Harga Beli</label>
                            <input type="number" class="form-control" name="harga_beli" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="number" class="form-control" name="harga_jual" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" name="stok" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection