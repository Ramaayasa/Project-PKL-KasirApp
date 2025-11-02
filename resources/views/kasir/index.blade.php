@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Transaksi Kasir</h2>
            <div>
                <a href="{{ route('kasir.riwayat') }}" class="btn btn-secondary">📋 Riwayat</a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <button class="btn btn-primary active">Cash</button>
                <button class="btn btn-outline-primary">Piutang</button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Header Invoice & Search -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>No. Invoice: <strong id="invoiceNumber">{{ $kodeTransaksi }}</strong></h4>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Barcode / Kode Barang"
                                autofocus>
                            <button class="btn btn-primary" onclick="searchProduct()"type="button">
                                <i class="bi bi-search"></i> Cari (Enter)
                            </button>
                            <button class="btn btn-info text-white" onclick="openModalBarang()" type="button">
                                <i class="bi bi-list-ul"></i> List Barang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabel Keranjang -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered" id="cartTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No.</th>
                                <th>Nama Barang</th>
                                <th width="150">Harga</th>
                                <th width="100">QTY</th>
                                <th width="150">Subtotal</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            <tr class="text-center text-muted">
                                <td colspan="6" class="py-4">
                                    Belum ada barang. Scan atau cari barang untuk memulai transaksi.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Form & Summary -->
                <div class="row">
                    <!-- Form Input -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer</label>
                            <select class="form-select" id="customerId">
                                <option value="">-- Pilih Customer --</option>
                                <option value="1">Customer Umum</option>
                            </select>
                            <small><a href="#" class="text-primary">+ Tambah Customer</a></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kurir</label>
                            <select class="form-select" id="kurirId">
                                <option value="">-- Pilih Kurir --</option>
                                <option value="pickup">Ambil Sendiri</option>
                                <option value="jne">JNE</option>
                                <option value="jnt">J&T</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipe Pembayaran</label>
                            <select class="form-select" id="paymentType">
                                <option value="">-- Pilih Pembayaran --</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="fw-bold">Total</h5>
                                    <h5 class="fw-bold">Rp. <span id="totalAmount">0</span></h5>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Ongkir</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span>Rp. <span id="shippingCost">0</span></span>
                                        <button class="btn btn-sm btn-danger" onclick="removeShipping()">×</button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                    <span class="fw-bold">Sub Total</span>
                                    <span class="fw-bold">Rp. <span id="subTotal">0</span></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-danger">Bayar</label>
                                    <input type="number" class="form-control form-control-lg text-end" 
                                           id="paymentInput" placeholder="0" oninput="calculateChange()">
                                </div>

                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fw-bold">Kembali</span>
                                    <span class="fw-bold text-success">Rp. <span id="changeAmount">0</span></span>
                                </div>

                                <button class="btn btn-primary btn-lg w-100" onclick="processPayment()">
                                    <i class="bi bi-cart-check"></i> Simpan Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Data Barang Keseluruhan -->
    <div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalBarangLabel">
                        <i class="bi bi-box-seam"></i> Data Barang Keseluruhan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Box -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Show</label>
                            <select class="form-select form-select-sm" id="entriesPerPage" onchange="renderBarangTable(allBarangs)">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <small class="text-muted">entries</small>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Search:</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="searchBarangModal" 
                                   placeholder="Cari kode barang, barcode, atau nama...">
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div class="text-center py-5" id="loadingBarang" style="display: none;">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted fw-semibold">Memuat data barang...</p>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive" id="tableBarangWrapper">
                        <table class="table table-hover table-bordered align-middle" id="tableBarangModal">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center">No.</th>
                                    <th>Kode Barang</th>
                                    <th>Nama</th>
                                    <th width="120" class="text-end">Harga</th>
                                    <th width="80" class="text-center">Stock</th>
                                    <th width="100" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBarangBody">
                                <!-- Data akan diload via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        #cartTable tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>

    @push('scripts')
        <script>
        let cart = [];
        let itemCounter = 0;
        let allBarangs = []; // Simpan semua data barang

        // CSRF Token untuk AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Load semua barang untuk modal
        function loadAllBarangs() {
            const loadingEl = document.getElementById('loadingBarang');
            const tableWrapper = document.getElementById('tableBarangWrapper');

            loadingEl.style.display = 'block';
            tableWrapper.style.display = 'none';
            document.getElementById('tableBarangBody').innerHTML = '';

            fetch('{{ route("kasir.getAllBarang") }}', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allBarangs = data.data;
                    renderBarangTable(allBarangs);
                    tableWrapper.style.display = 'block';
                } else {
                    showAlert('Gagal memuat data barang', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat memuat data', 'danger');
            })
            .finally(() => {
                loadingEl.style.display = 'none';
            });
        }

        // Render tabel barang di modal
        function renderBarangTable(barangs) {
            const tbody = document.getElementById('tableBarangBody');

            if (barangs.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Tidak ada data barang ditemukan
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = barangs.map((barang, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td><span class="badge bg-info">${barang.kode_barang}</span></td>
                    <td>${barang.nama}</td>
                    <td>Rp. ${formatNumber(barang.harga)}</td>
                    <td>
                        <span class="badge ${barang.stok > 10 ? 'bg-success' : (barang.stok > 0 ? 'bg-warning' : 'bg-danger')}">
                            ${barang.stok}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" 
                                onclick="pilihBarangDariModal(${barang.id}, '${barang.nama.replace(/'/g, "\\'")}', ${barang.harga}, ${barang.stok})"
                                ${barang.stok <= 0 ? 'disabled' : ''}>
                            <i class="bi bi-cart-plus"></i> Pilih
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Open modal barang
        function openModalBarang() {
            const modal = new bootstrap.Modal(document.getElementById('modalBarang'));
            modal.show();
            loadAllBarangs();
        }

        // Pilih barang dari modal
        function pilihBarangDariModal(id, nama, harga, stok) {
            const barang = {
                id: id,
                nama: nama,
                harga: parseFloat(harga),
                stok: stok
            };

            addToCart(barang);
            showAlert(`${nama} ditambahkan ke keranjang`, 'success');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
            modal.hide();
        }

        // Search barang di modal
        document.getElementById('searchBarangModal')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            const filtered = allBarangs.filter(barang => {
                return barang.nama.toLowerCase().includes(searchTerm) ||
                       barang.kode_barang.toLowerCase().includes(searchTerm) ||
                       (barang.barcode && barang.barcode.toLowerCase().includes(searchTerm));
            });

            renderBarangTable(filtered);
        });

        // Search Product (dengan Enter atau klik tombol)
        function searchProduct() {
            const searchValue = document.getElementById('searchInput').value.trim();

            if (!searchValue) {
                showAlert('Masukkan barcode atau kode barang!', 'warning');
                return;
            }

            // Show loading
            const searchBtn = document.querySelector('.btn-primary');
            const originalText = searchBtn.innerHTML;
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mencari...';

            fetch(`{{ route('kasir.search') }}?search=${encodeURIComponent(searchValue)}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    addToCart(data.data);
                    document.getElementById('searchInput').value = '';
                    document.getElementById('searchInput').focus();
                    showAlert(`Berhasil menambahkan: ${data.data.nama}`, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const message = error.message || 'Barang tidak ditemukan!';
                showAlert(message, 'danger');
            })
            .finally(() => {
                searchBtn.disabled = false;
                searchBtn.innerHTML = originalText;
            });
        }

        // Show Alert
        function showAlert(message, type = 'info') {
            // Remove existing alerts
            const existingAlert = document.querySelector('.alert-notification');
            if (existingAlert) {
                existingAlert.remove();
            }

            // Create new alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-notification`;
            alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 3 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        // Add to Cart
        function addToCart(barang) {
            // Cek apakah barang sudah ada di cart
            const existingItem = cart.find(item => item.barang_id === barang.id);

            if (existingItem) {
                // Cek stok
                if (existingItem.jumlah >= barang.stok) {
                    showAlert(`Stok tidak cukup! Stok tersedia: ${barang.stok}`, 'warning');
                    return;
                }
                existingItem.jumlah++;
                existingItem.subtotal = existingItem.harga * existingItem.jumlah;
            } else {
                itemCounter++;
                cart.push({
                    id: itemCounter,
                    no: itemCounter,
                    barang_id: barang.id,
                    nama: barang.nama,
                    harga: parseFloat(barang.harga),
                    jumlah: 1,
                    stok: barang.stok, // Simpan info stok
                    subtotal: parseFloat(barang.harga)
                });
            }

            renderCart();
            calculateTotal();
        }

        // Render Cart
        function renderCart() {
            const cartBody = document.getElementById('cartBody');

            if (cart.length === 0) {
                cartBody.innerHTML = `
                    <tr class="text-center text-muted">
                        <td colspan="6" class="py-4">Belum ada barang. Scan atau cari barang untuk memulai transaksi.</td>
                    </tr>
                `;
                return;
            }

            cartBody.innerHTML = cart.map(item => `
                <tr>
                    <td>${item.no}</td>
                    <td>${item.nama}</td>
                    <td>Rp. ${formatNumber(item.harga)}</td>
                    <td>
                        <input type="number" value="${item.jumlah}" min="1" 
                               class="form-control form-control-sm text-center" 
                               style="width: 70px;"
                               onchange="updateQty(${item.id}, this.value)">
                    </td>
                    <td>Rp. ${formatNumber(item.subtotal)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger" onclick="removeItem(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Update Quantity
        function updateQty(id, newQty) {
            const item = cart.find(i => i.id === id);
            if (item) {
                const qty = parseInt(newQty) || 1;

                // Validasi stok
                if (qty > item.stok) {
                    showAlert(`Stok tidak cukup! Stok tersedia: ${item.stok}`, 'warning');
                    renderCart(); // Reset ke nilai sebelumnya
                    return;
                }

                item.jumlah = qty;
                item.subtotal = item.harga * item.jumlah;
                renderCart();
                calculateTotal();
            }
        }

        // Remove Item
        function removeItem(id) {
            cart = cart.filter(i => i.id !== id);
            renderCart();
            calculateTotal();
        }

        // Calculate Total
        function calculateTotal() {
            const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
            const shipping = parseFloat(document.getElementById('shippingCost').textContent.replace(/\./g, '')) || 0;
            const subTotal = total + shipping;

            document.getElementById('totalAmount').textContent = formatNumber(total);
            document.getElementById('subTotal').textContent = formatNumber(subTotal);

            calculateChange();
        }

        // Calculate Change
        function calculateChange() {
            const subTotal = parseFloat(document.getElementById('subTotal').textContent.replace(/\./g, '')) || 0;
            const payment = parseFloat(document.getElementById('paymentInput').value) || 0;
            const change = payment - subTotal;

            document.getElementById('changeAmount').textContent = 
                change >= 0 ? formatNumber(change) : '0';
        }

        // Remove Shipping
        function removeShipping() {
            document.getElementById('shippingCost').textContent = '0';
            calculateTotal();
        }

        // Process Payment
        function processPayment() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            const subTotal = parseFloat(document.getElementById('subTotal').textContent.replace(/\./g, '')) || 0;
            const payment = parseFloat(document.getElementById('paymentInput').value) || 0;

            if (payment < subTotal) {
                alert('Pembayaran kurang! Silakan masukkan jumlah yang tepat.');
                return;
            }

            const data = {
                items: cart.map(item => ({
                    barang_id: item.barang_id,
                    jumlah: item.jumlah,
                    harga: item.harga,
                    subtotal: item.subtotal
                })),
                total: parseFloat(document.getElementById('totalAmount').textContent.replace(/\./g, '')),
                bayar: payment,
                kembalian: payment - subTotal
            };

            fetch('{{ route("kasir.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Transaksi berhasil! Invoice: ' + data.data.kode_transaksi);

                    // Reset form
                    cart = [];
                    itemCounter = 0;
                    renderCart();
                    document.getElementById('paymentInput').value = '';
                    calculateTotal();

                    // Reload untuk generate invoice baru
                    location.reload();
                } else {
                    alert('Gagal menyimpan transaksi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan transaksi');
            });
        }

        // Format Number
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Enter key untuk search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchProduct();
            }
        });
        </script>
    @endpush
@endsection