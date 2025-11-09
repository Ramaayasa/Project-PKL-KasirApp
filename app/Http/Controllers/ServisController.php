<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    public function create()
    {
        $kategoriServis = \App\Models\Kategori::where('tipe', 'servis')
            ->orderBy('nama', 'asc')
            ->get();

        return view('servis.create', compact('kategoriServis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'keluhan' => 'required|string',
            'no_hp' => 'nullable|string|max:20',
            'tipe_barang' => 'nullable|string|max:100',
            'seri_barang' => 'nullable|string|max:100',
            'status' => 'nullable|in:pending,proses,selesai',
        ]);

        // --- FORMAT NOMOR HP OTOMATIS ---
        $no_hp = preg_replace('/[^0-9]/', '', $request->no_hp); // ambil angka saja
        if (strpos($no_hp, '0') === 0) {
            $no_hp = '62' . substr($no_hp, 1);
        }

        // Generate kode servis
        $last = Servis::latest()->first();
        $kode = 'SRV' . now()->format('Ymd') . str_pad(($last ? $last->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        Servis::create([
            'kode_servis' => $kode,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $no_hp,
            'alamat' => $request->alamat,
            'tipe_barang' => $request->tipe_barang,
            'seri_barang' => $request->seri_barang,
            'keluhan' => $request->keluhan,
            'kelengkapan' => $request->kelengkapan,
            'password' => $request->password,
            'warna_barang' => $request->warna_barang,
            'status' => $request->status ?? 'proses',
        ]);

        return redirect()->route('servis.index')->with('success', 'Data servis berhasil ditambahkan!');
    }

    public function index()
    {
        $servis = Servis::latest()->get();
        return view('servis.index', compact('servis'));
    }

    public function show($id)
    {
        $servis = Servis::findOrFail($id);
        return view('servis.show', compact('servis'));
    }

    public function edit($id)
    {
        $servis = Servis::findOrFail($id);
        $kategoriServis = \App\Models\Kategori::where('tipe', 'servis')
            ->orderBy('nama', 'asc')
            ->get();
        return view('servis.edit', compact('servis', 'kategoriServis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'keluhan' => 'required|string',
            'status' => 'nullable|in:pending,proses,selesai',
        ]);

        $servis = Servis::findOrFail($id);

        // --- FORMAT NOMOR HP OTOMATIS (UPDATE JUGA) ---
        $no_hp = preg_replace('/[^0-9]/', '', $request->no_hp);
        if (strpos($no_hp, '0') === 0) {
            $no_hp = '62' . substr($no_hp, 1);
        }

        $servis->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $no_hp,
            'alamat' => $request->alamat,
            'tipe_barang' => $request->tipe_barang,
            'seri_barang' => $request->seri_barang,
            'keluhan' => $request->keluhan,
            'kelengkapan' => $request->kelengkapan,
            'password' => $request->password,
            'warna_barang' => $request->warna_barang,
            'status' => $request->status ?? 'pending',
        ]);

        return redirect()->route('servis.index')->with('success', 'Data servis berhasil diupdate!');
    }

    public function destroy($id)
    {
        $servis = Servis::findOrFail($id);
        $servis->delete();
        return redirect()->route('servis.index')->with('success', 'Data servis berhasil dihapus!');
    }
}
