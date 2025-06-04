<?php

namespace App\Http\Controllers;

use App\Models\KomponenMasakan; // Pastikan ini sudah di-import
use Illuminate\Http\Request;

class KomponenMasakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $komponenMasakans = KomponenMasakan::query()
                            ->when($search, function($query, $search) {
                                $query->where('nama_komponen', 'like', '%' . $search . '%');
                            })
                            ->orderBy('nama_komponen', 'asc') // Urutkan berdasarkan nama
                            ->paginate(10); // Paginate the results

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
     * Tidak akan digunakan secara langsung untuk KomponenMasakan, tapi tetap dipertahankan.
     */
    public function show(KomponenMasakan $komponenMasakan)
    {
        // return view('admin.komponen_masakan.show', compact('komponenMasakan'));
        abort(404); // Kita tidak perlu halaman show terpisah untuk komponen masakan
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
            // Periksa apakah error disebabkan oleh foreign key constraint
            if ($e->getCode() == "23000") { // Kode SQLSTATE untuk integrity constraint violation
                return redirect()->route('admin.komponen-masakan.index')
                                 ->with('error', 'Tidak dapat menghapus komponen masakan karena sedang digunakan oleh beberapa produk.');
            }
            return redirect()->route('admin.komponen-masakan.index')->with('error', 'Terjadi kesalahan saat menghapus komponen masakan.');
        }
    }
}