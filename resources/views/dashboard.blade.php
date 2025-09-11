@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Barang</h5>
                    <h3>{{ \App\Models\Barang::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Servis Pending</h5>
                    <h3>{{ \App\Models\Servis::where('status', 'pending')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5>Transaksi Hari Ini</h5>
                    <h3>{{ \App\Models\Servis::where('status', 'pending')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
