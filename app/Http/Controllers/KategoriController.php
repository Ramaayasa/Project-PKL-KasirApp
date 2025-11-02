<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori
     */
    public function index(Request $request)
    {
        $tipe = $request->get('tipe', 'barang'); // Default: barang

        $kategoris = Kategori::where('tipe', $tipe)
            ->latest()
            ->get();

        return view('kategori.index', compact('kategoris', 'tipe'));
    }

    /**
     * Menampilkan form tambah kategori
     */
    public function create(Request $request)
    {
        $tipe = $request->get('tipe', 'barang');
        return view('kategori.create', compact('tipe'));
    }

    /**
     * Menyimpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'tipe' => 'required|in:barang,servis',
            'deskripsi' => 'nullable|string',
        ]);

        Kategori::create([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kategori.index', ['tipe' => $request->tipe])
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit
     */
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kategori.index', ['tipe' => $kategori->tipe])
            ->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Hapus kategori
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $tipe = $kategori->tipe;
        $kategori->delete();

        return redirect()->route('kategori.index', ['tipe' => $tipe])
            ->with('success', 'Kategori berhasil dihapus!');
    }
}