<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index()
    {
        $barangs = Barang::with('kategori')->latest()->get();
        return view('barang.index', compact('barangs'));
    }

    // Menampilkan form tambah barang
    public function create()
    {
        // Ambil kategori barang untuk dropdown
        $kategoris = Kategori::where('tipe', 'barang')->orderBy('nama', 'asc')->get();
        return view('barang.create', compact('kategoris'));
    }

    // Menyimpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|max:50|unique:barangs,barcode',
            'deskripsi' => 'nullable|string',
        ]);

        Barang::create([
            'kode_barang' => 'item-'.time(),
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'barcode' => $request->barcode,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::where('tipe', 'barang')->orderBy('nama', 'asc')->get();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    // Update barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|max:50|unique:barangs,barcode,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'barcode' => $request->barcode,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diupdate!');
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}