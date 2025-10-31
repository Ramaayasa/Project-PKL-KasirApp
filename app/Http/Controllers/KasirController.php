<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Menampilkan halaman kasir (POS)
     */
    public function index()
    {
        $barangs = Barang::all();
        $kodeTransaksi = $this->generateKodeTransaksi();

        return view('kasir.index', compact('barangs', 'kodeTransaksi'));
    }

    /**
     * API: Cari barang berdasarkan barcode/kode
     */
    public function searchBarang(Request $request)
    {
        $search = $request->search;

        $barang = Barang::where('kode_barang', $search)
            ->orWhere('barcode', $search)
            ->first();

        if ($barang) {
            return response()->json([
                'success' => true,
                'data' => $barang
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan'
        ], 404);
    }

    /**
     * Simpan transaksi
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'bayar' => 'required|numeric|min:' . $request->total,
            'kembalian' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Simpan header transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => $this->generateKodeTransaksi(),
                'tanggal' => now(),
                'total' => $request->total,
                'bayar' => $request->bayar,
                'kembalian' => $request->kembalian,
            ]);

            // Simpan detail transaksi
            foreach ($request->items as $item) {
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update stok barang
                $barang = Barang::find($item['barang_id']);
                $barang->stok -= $item['jumlah'];
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => $transaksi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan riwayat transaksi
     */
    public function riwayat()
    {
        $transaksi = Transaksi::with('details.barang')
            ->latest()
            ->paginate(20);

        return view('kasir.riwayat', compact('transaksi'));
    }

    /**
     * Detail transaksi
     */
    public function show($id)
    {
        $transaksi = Transaksi::with('details.barang')->findOrFail($id);
        return view('kasir.detail', compact('transaksi'));
    }

    /**
     * Generate kode transaksi unik
     */
    private function generateKodeTransaksi()
    {
        $prefix = 'TRX';
        $date = date('Ymd');

        $lastTransaksi = Transaksi::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaksi) {
            $lastNumber = intval(substr($lastTransaksi->kode_transaksi, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Cetak struk (optional)
     */
    public function printStruk($id)
    {
        $transaksi = Transaksi::with('details.barang')->findOrFail($id);
        return view('kasir.struk', compact('transaksi'));
    }
}