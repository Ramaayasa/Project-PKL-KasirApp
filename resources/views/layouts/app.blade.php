<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi Kasir & Servis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Ganesha Computer</a>
            <div>
                <a href="{{ route('barang.index') }}" class="btn btn-outline-light btn-sm">Barang</a>
                <a href="{{ route('kasir.index') }}" class="btn btn-outline-light btn-sm">Kasir</a>
                <a href="{{ route('servis.index') }}" class="btn btn-outline-light btn-sm">Servis</a>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
