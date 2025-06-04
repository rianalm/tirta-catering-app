<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KomponenMasakan; // Pastikan ini di-import
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $produks = Produk::query()
                    ->when($search, function($query, $search) {
                        $query->where('nama_produk', 'like', '%' . $search . '%')
                              ->orWhere('deskripsi_produk', 'like', '%' . $search . '%');
                    })
                    ->orderBy('nama_produk', 'asc')
                    ->paginate(10);

        return view('admin.produk.index', compact('produks', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $komponenMasakans = KomponenMasakan::orderBy('nama_komponen')->get(); // Ambil semua komponen masakan
        return view('admin.produk.create', compact('komponenMasakans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255|unique:produks,nama_produk',
            'deskripsi_produk' => 'nullable|string', // Sudah sesuai dengan DB
            'harga_jual' => 'required|numeric|min:0', // Sudah sesuai dengan DB
            'satuan' => 'nullable|string|max:50', // Sudah sesuai dengan DB (jika migration sudah dijalankan)
            'komponen_masakan' => 'array', // Array untuk ID komponen masakan yang dipilih
            'komponen_masakan.*.id' => 'required_with:komponen_masakan|exists:komponen_masakans,id', // Validasi ID komponen
            'komponen_masakan.*.jumlah' => 'required_with:komponen_masakan.*.id|integer|min:1', // Validasi jumlah
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.unique' => 'Nama produk ini sudah ada.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
            'komponen_masakan.*.jumlah.required_with' => 'Jumlah porsi untuk komponen yang dipilih wajib diisi.',
            'komponen_masakan.*.jumlah.integer' => 'Jumlah porsi harus berupa angka bulat.',
            'komponen_masakan.*.jumlah.min' => 'Jumlah porsi minimal 1.',
        ]);

        $produk = Produk::create($validated);

        // Simpan relasi dengan komponen masakan
        $komponenData = [];
        if (isset($validated['komponen_masakan'])) {
            foreach ($validated['komponen_masakan'] as $komponen) {
                $komponenData[$komponen['id']] = ['jumlah_per_porsi' => $komponen['jumlah']];
            }
        }
        $produk->komponenMasakans()->sync($komponenData);

        return redirect()->route('admin.produks.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        // Tambahkan eager loading untuk komponenMasakans jika ingin menampilkan detailnya
        $produk->load('komponenMasakans');
        return view('admin.produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $komponenMasakans = KomponenMasakan::orderBy('nama_komponen')->get(); // Ambil semua komponen
        // Ambil juga komponen yang sudah terhubung dengan produk ini
        $produkKomponen = $produk->komponenMasakans->keyBy('id')->map(function($item) {
            return $item->pivot->jumlah_per_porsi;
        });
        // dd($produkKomponen); // Untuk debugging, Anda bisa melihat isinya
        return view('admin.produk.edit', compact('produk', 'komponenMasakans', 'produkKomponen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255|unique:produks,nama_produk,' . $produk->id,
            'deskripsi_produk' => 'nullable|string', // Sudah sesuai dengan DB
            'harga_jual' => 'required|numeric|min:0', // Sudah sesuai dengan DB
            'satuan' => 'nullable|string|max:50', // Sudah sesuai dengan DB
            'komponen_masakan' => 'array', // Array untuk ID komponen masakan yang dipilih
            'komponen_masakan.*.id' => 'required_with:komponen_masakan|exists:komponen_masakans,id', // Validasi ID komponen
            'komponen_masakan.*.jumlah' => 'required_with:komponen_masakan.*.id|integer|min:1', // Validasi jumlah
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.unique' => 'Nama produk ini sudah ada.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
            'komponen_masakan.*.jumlah.required_with' => 'Jumlah porsi untuk komponen yang dipilih wajib diisi.',
            'komponen_masakan.*.jumlah.integer' => 'Jumlah porsi harus berupa angka bulat.',
            'komponen_masakan.*.jumlah.min' => 'Jumlah porsi minimal 1.',
        ]);

        $produk->update($validated);

        // Simpan relasi dengan komponen masakan menggunakan sync
        $komponenData = [];
        if (isset($validated['komponen_masakan'])) {
            foreach ($validated['komponen_masakan'] as $komponen) {
                $komponenData[$komponen['id']] = ['jumlah_per_porsi' => $komponen['jumlah']];
            }
        }
        $produk->komponenMasakans()->sync($komponenData);

        return redirect()->route('admin.produks.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        try {
            // Relasi pivot akan dihapus secara otomatis karena onDelete('cascade') pada migration pivot table
            $produk->delete();
            return redirect()->route('admin.produks.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") { // SQLSTATE for integrity constraint violation
                return redirect()->route('admin.produks.index')
                                 ->with('error', 'Tidak dapat menghapus produk ini karena masih memiliki pesanan terkait.');
            }
            return redirect()->route('admin.produks.index')->with('error', 'Terjadi kesalahan saat menghapus produk.');
        }
    }
}