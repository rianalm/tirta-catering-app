<?php

namespace App\Http\Controllers;

use App\Models\KomponenMasakan;
use Illuminate\Http\Request;

class KomponenMasakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Tambahkan Request $request
    {
        // Ambil input pencarian dari request
        $search = $request->input('search');

        // Mulai query
        $query = KomponenMasakan::query();

        // Jika ada input pencarian, tambahkan kondisi 'where'
        if ($search) {
            $query->where('nama_komponen', 'like', '%' . $search . '%');
        }

        // Lanjutkan dengan pengurutan dan paginasi
        $komponenMasakans = $query->orderBy('nama_komponen', 'asc')
                                  ->paginate(10);

        // Kirim data dan variabel search ke view
        return view('admin.komponen_masakan.index', compact('komponenMasakans', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.komponen_masakan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_komponen' => 'required|string|max:255|unique:komponen_masakans,nama_komponen',
            'satuan_dasar' => 'nullable|string|max:50',
        ], [
            'nama_komponen.required' => 'Nama komponen masakan wajib diisi.',
            'nama_komponen.unique' => 'Nama komponen masakan ini sudah ada.',
        ]);

        KomponenMasakan::create($request->all());

        return redirect()->route('admin.komponen-masakan.index')->with('success', 'Komponen masakan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KomponenMasakan $komponenMasakan)
    {
        abort(404); // Kita tidak perlu halaman show terpisah
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KomponenMasakan $komponenMasakan)
    {
        return view('admin.komponen_masakan.edit', compact('komponenMasakan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KomponenMasakan $komponenMasakan)
    {
        $request->validate([
            'nama_komponen' => 'required|string|max:255|unique:komponen_masakans,nama_komponen,' . $komponenMasakan->id,
            'satuan_dasar' => 'nullable|string|max:50',
        ], [
            'nama_komponen.required' => 'Nama komponen masakan wajib diisi.',
            'nama_komponen.unique' => 'Nama komponen masakan ini sudah ada.',
        ]);

        $komponenMasakan->update($request->all());

        return redirect()->route('admin.komponen-masakan.index')->with('success', 'Komponen masakan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KomponenMasakan $komponenMasakan)
    {
        try {
            $komponenMasakan->delete();
            return redirect()->route('admin.komponen-masakan.index')->with('success', 'Komponen masakan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") { // Kode SQLSTATE untuk integrity constraint violation
                return redirect()->route('admin.komponen-masakan.index')
                                 ->with('error', 'Gagal menghapus! Komponen ini masih digunakan oleh beberapa produk.');
            }
            return redirect()->route('admin.komponen-masakan.index')->with('error', 'Terjadi kesalahan saat menghapus komponen masakan.');
        }
    }
}