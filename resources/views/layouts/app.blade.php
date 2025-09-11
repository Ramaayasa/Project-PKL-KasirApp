<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sistem Kasir & Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">KasirApp</a>
            <div>
                <a class="nav-link d-inline text-white" href="{{ route('barang.index') }}">Barang</a>
                <a class="nav-link d-inline text-white" href="{{ route('servis.index') }}">Servis</a>
                <a class="nav-link d-inline text-white" href="{{ route('kasir.index') }}">Kasir</a>
            </div>
        </div>
    </nav>

    {{-- Konten Halaman --}}
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
