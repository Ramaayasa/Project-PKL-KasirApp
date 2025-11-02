<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function index()
    {
        $kodeTransaksi = $this->generateKodeTransaksi();
        return view('kasir.index', compact('kodeTransaksi'));
    }

    private function generateKodeTransaksi()
    {
        $today = Carbon::now()->format('Ymd');
        $prefix = 'TRX' . $today;

        $lastTransaction = Transaksi::where('kode_transaksi', 'like', $prefix . '%')
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        $newNumber = $lastTransaction
            ? intval(substr($lastTransaction->kode_transaksi, -5)) + 1
            : 1;

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function search(Request $request)
    {
        try {
            $search = trim($request->input('search'));

            if (empty($search)) {
                return response()->json(['success' => false, 'message' => 'Masukkan barcode, kode barang, atau nama barang!'], 400);
            }

            $barang = Barang::where('kode_barang', $search)
                ->orWhere('barcode', $search)
                ->orWhere('nama', 'like', "%{$search}%")
                ->first();

            if (!$barang) {
                return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan!'], 404);
            }

            if ($barang->stok < 1) {
                return response()->json(['success' => false, 'message' => 'Stok barang habis!'], 400);
            }

            return response()->json(['success' => true, 'data' => $barang]);
        } catch (\Exception $e) {
            Log::error('Error searching barang: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function listBarang(Request $request)
    {
        try {
            $perPage = $request->input('entries', 10);
            $search = $request->input('search', '');

            $query = Barang::query()->where('status', 1)->where('stok', '>', 0);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            }

            $barang = $query->orderBy('nama', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $barang->items(),
                'pagination' => [
                    'current_page' => $barang->currentPage(),
                    'last_page' => $barang->lastPage(),
                    'per_page' => $barang->perPage(),
                    'total' => $barang->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading barang list: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memuat data'], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.barang_id' => 'required|exists:barang,id',
                'items.*.jumlah' => 'required|integer|min:1',
                'items.*.harga' => 'required|numeric|min:0',
                'items.*.subtotal' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'bayar' => 'required|numeric|min:0',
                'kembalian' => 'required|numeric|min:0'
            ]);

            $kodeTransaksi = $this->generateKodeTransaksi();

            // Simpan transaksi dulu biar id-nya pasti ada
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'tanggal' => now(),
                'total' => $request->total,
                'bayar' => $request->bayar,
                'kembalian' => $request->kembalian,
                'user_id' => auth()->id() ?? 1,
                'status' => 'selesai'
            ]);

            foreach ($request->items as $item) {
                $barang = Barang::findOrFail($item['barang_id']);

                if ($barang->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$barang->nama} tidak mencukupi");
                }

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);

                $barang->decrement('stok', $item['jumlah']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => ['id' => $transaksi->id, 'kode_transaksi' => $transaksi->kode_transaksi]
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error storing transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function riwayat()
    {
        $transaksi = Transaksi::with(['user', 'details.barang'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('kasir.riwayat', compact('transaksi'));
    }

    public function detail($id)
    {
        $transaksi = Transaksi::with(['user', 'details.barang'])->findOrFail($id);
        return view('kasir.detail', compact('transaksi'));
    }

    public function printStruk($id)
    {
        $transaksi = Transaksi::with(['user', 'details.barang'])->findOrFail($id);
        return view('kasir.print', compact('transaksi'));
    }
}
        