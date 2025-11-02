<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ganesha Computer') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --sidebar-width: 280px;
            --navbar-height: 60px;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --sidebar-active: #3498db;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: #2c3e50;
            height: var(--navbar-height);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .navbar-brand i {
            font-size: 1.5rem;
            margin-right: 10px;
        }

        .navbar .btn-group .btn {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-left: 5px;
        }

        .navbar .btn-group .btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: var(--sidebar-bg);
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1020;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1a252f;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #34495e;
            border-radius: 3px;
        }

        /* Menu Items */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-item {
            position: relative;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-link:hover {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: var(--sidebar-active);
        }

        .menu-link.active {
            background: var(--sidebar-active);
            color: white;
            border-left-color: white;
        }

        .menu-link i {
            font-size: 20px;
            width: 30px;
            margin-right: 12px;
        }

        .menu-link .bi-circle {
            font-size: 10px;
        }

        .menu-link .bi-circle-fill {
            font-size: 10px;
        }

        /* Dropdown Toggle */
        .menu-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            color: #ecf0f1;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-toggle:hover {
            background: var(--sidebar-hover);
            border-left-color: var(--sidebar-active);
        }

        .menu-toggle i.toggle-icon {
            transition: transform 0.3s ease;
            font-size: 14px;
        }

        .menu-toggle.collapsed i.toggle-icon {
            transform: rotate(0deg);
        }

        .menu-toggle:not(.collapsed) i.toggle-icon {
            transform: rotate(180deg);
        }

        .menu-toggle .menu-label {
            display: flex;
            align-items: center;
        }

        .menu-toggle .menu-label i {
            font-size: 20px;
            width: 30px;
            margin-right: 12px;
        }

        /* Submenu */
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background: #1a252f;
        }

        .submenu .menu-link {
            padding-left: 60px;
            font-size: 0.95rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 30px;
            min-height: calc(100vh - var(--navbar-height));
            background: #f8f9fa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-toggler {
                display: block !important;
            }
        }

        .navbar-toggler {
            display: none;
            border: none;
            color: white;
            font-size: 1.5rem;
        }

        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: 100%;
            height: calc(100vh - var(--navbar-height));
            background: rgba(0, 0, 0, 0.5);
            z-index: 1015;
        }

        .sidebar-overlay.show {
            display: block;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-building"></i>
                Ganesha Computer
            </a>

            <div class="btn-group">
                <a href="{{ route('barang.index') }}" class="btn btn-sm">Master</a>
                <a href="{{ route('kasir.index') }}" class="btn btn-sm">Kasir</a>
                <a href="{{ route('servis.index') }}" class="btn btn-sm">Servis</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay (untuk mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <!-- Menu Master -->
            <li class="menu-item">
                <button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#masterMenu" aria-expanded="true">
                    <span class="menu-label">
                        <i class="bi bi-shop"></i>
                        <span>Master</span>
                    </span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </button>
                <div class="collapse show" id="masterMenu">
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('barang.index') }}"
                                class="menu-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                                <i class="bi {{ request()->routeIs('barang.*') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                Data Barang
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kategori.index', ['tipe' => 'barang']) }}"
                                class="menu-link {{ request()->routeIs('kategori.*') && request()->get('tipe') == 'barang' ? 'active' : '' }}">
                                <i
                                    class="bi {{ request()->routeIs('kategori.*') && request()->get('tipe') == 'barang' ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                Kategori Barang
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kategori.index', ['tipe' => 'servis']) }}"
                                class="menu-link {{ request()->routeIs('kategori.*') && request()->get('tipe') == 'servis' ? 'active' : '' }}">
                                <i
                                    class="bi {{ request()->routeIs('kategori.*') && request()->get('tipe') == 'servis' ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                Kategori Servis
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Menu Kasir -->
            <li class="menu-item">
                <a href="{{ route('kasir.index') }}"
                    class="menu-link {{ request()->routeIs('kasir.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-register"></i>
                    Kasir
                </a>
            </li>

            <!-- Menu Servis -->
            <li class="menu-item">
                <a href="{{ route('servis.index') }}"
                    class="menu-link {{ request()->routeIs('servis.index') || request()->routeIs('servis.create') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i>
                    Servis
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle untuk Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Auto collapse menu di mobile setelah klik link
        const menuLinks = document.querySelectorAll('.sidebar .menu-link');
        menuLinks.forEach(link => {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>