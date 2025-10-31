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
                            <button class="btn btn-primary" onclick="searchProduct()">
                                <i class="bi bi-search"></i> Cari
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
                                    <input type="number" class="form-control form-control-lg text-end" id="paymentInput"
                                        placeholder="0" oninput="calculateChange()">
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

    <style>
        .form-control:focus,
        .form-select:focus {
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

            // CSRF Token untuk AJAX
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Search Product
            function searchProduct() {
                const searchValue = document.getElementById('searchInput').value.trim();

                if (!searchValue) {
                    alert('Masukkan barcode atau kode barang!');
                    return;
                }

                fetch(`{{ route('kasir.search') }}?search=${searchValue}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            addToCart(data.data);
                            document.getElementById('searchInput').value = '';
                            document.getElementById('searchInput').focus();
                        } else {
                            alert('Barang tidak ditemukan!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mencari barang');
                    });
            }

            // Add to Cart
            function addToCart(barang) {
                // Cek apakah barang sudah ada di cart
                const existingItem = cart.find(item => item.barang_id === barang.id);

                if (existingItem) {
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
                    item.jumlah = parseInt(newQty) || 1;
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
            document.getElementById('searchInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    searchProduct();
                }
            });
        </script>
    @endpush
@endsection