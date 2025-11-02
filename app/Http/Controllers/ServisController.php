<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    // Method untuk menampilkan form
    public function create()
    {
        // Ambil kategori servis untuk dropdown/autocomplete
        $kategoriServis = \App\Models\Kategori::where('tipe', 'servis')
            ->orderBy('nama', 'asc')
            ->get();

        return view('servis.create', compact('kategoriServis'));
    }

    // Method untuk menyimpan data (SUDAH DIPERBAIKI)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'keluhan' => 'required|string',
            'kontak' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,proses,selesai',
        ]);

        // Insert data baru (kode_servis akan auto-generate dari Model)
        Servis::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'keluhan' => $request->keluhan,
            'kontak' => $request->kontak,
            'deskripsi' => $request->deskripsi,
            'biaya' => $request->biaya,
            'status' => $request->status ?? 'pending',
        ]);

        return redirect()->route('servis.index')
            ->with('success', 'Data servis berhasil ditambahkan!');
    }

    // Method untuk menampilkan list
    public function index()
    {
        $servis = Servis::latest()->get();
        return view('servis.index', compact('servis'));
    }

    // Method untuk menampilkan detail
    public function show($id)
    {
        $servis = Servis::findOrFail($id);
        return view('servis.show', compact('servis'));
    }

    // Method untuk menampilkan form edit
    public function edit($id)
    {
        $servis = Servis::findOrFail($id);
        return view('servis.edit', compact('servis'));
    }

    // Method untuk update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'keluhan' => 'required|string',
            'kontak' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,proses,selesai',
        ]);

        $servis = Servis::findOrFail($id);

        // Update semua field kecuali kode_servis (biar gak berubah)
        $servis->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'keluhan' => $request->keluhan,
            'kontak' => $request->kontak,
            'deskripsi' => $request->deskripsi,
            'biaya' => $request->biaya,
            'status' => $request->status ?? 'pending',
        ]);

        return redirect()->route('servis.index')
            ->with('success', 'Data servis berhasil diupdate!');
    }

    // Method untuk hapus data
    public function destroy($id)
    {
        $servis = Servis::findOrFail($id);
        $servis->delete();

        return redirect()->route('servis.index')
            ->with('success', 'Data servis berhasil dihapus!');
    }
}