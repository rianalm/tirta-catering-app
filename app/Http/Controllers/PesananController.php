<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\ItemPesanan;
use Carbon\Carbon;

class PesananController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan dengan paginasi.
     * Dapat difilter berdasarkan status dan dicari berdasarkan kata kunci.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $searchQuery = $request->query('search');

        $query = Pesanan::with('itemPesanans.produk');

        if ($statusFilter) {
            $query->where('status_pesanan', $statusFilter);
        }

        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('nama_pelanggan', 'like', '%' . $searchQuery . '%')
                  ->orWhere('telepon_pelanggan', 'like', '%' . $searchQuery . '%')
                  ->orWhere('alamat_pengiriman', 'like', '%' . $searchQuery . '%')
                  ->orWhere('id', 'like', '%' . $searchQuery . '%');
            });
        }

        $pesanans = $query->orderBy('tanggal_pesanan', 'desc')->paginate(10);

        // Define a fixed list of possible statuses for the dropdown
        // This is safer than relying on existing data, ensuring all options are present.
        $allStatuses = ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];

        return view('admin.index_pesanan', compact('pesanans', 'statusFilter', 'searchQuery', 'allStatuses'));
    }

    /**
     * Menyimpan pesanan baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'required|string|max:20',
            'tanggal_pengiriman' => 'required|date',
            'alamat_pengiriman' => 'required|string',
            'waktu_pengiriman' => 'nullable|string|max:50',
            'catatan_khusus' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $totalHargaPesanan = 0;
        $itemsToStore = [];

        foreach ($request->input('items') as $item) {
            $produk = Produk::find($item['produk_id']);

            if ($produk && $produk->is_active) {
                $hargaSatuan = $produk->harga_jual;
                $jumlahPorsi = $item['jumlah'];
                $subtotalItem = $hargaSatuan * $jumlahPorsi;

                $totalHargaPesanan += $subtotalItem;

                $itemsToStore[] = [
                    'produk_id' => $produk->id,
                    'jumlah_porsi' => $jumlahPorsi,
                    'harga_satuan_saat_pesan' => $hargaSatuan,
                    'subtotal_item' => $subtotalItem,
                ];
            } else {
                return redirect()->back()->withErrors(['items' => 'Salah satu produk yang dipilih tidak valid atau tidak aktif.'])->withInput();
            }
        }

        $pesanan = Pesanan::create([
            'tanggal_pesanan' => Carbon::now(),
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
            'waktu_pengiriman' => $request->waktu_pengiriman,
            'nama_pelanggan' => $request->nama_pelanggan,
            'telepon_pelanggan' => $request->telepon_pelanggan,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'catatan_khusus' => $request->catatan_khusus,
            'status_pesanan' => 'pending',
            'total_harga' => $totalHargaPesanan,
        ]);

        $pesanan->itemPesanans()->createMany($itemsToStore);

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail dari pesanan tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $pesanan = Pesanan::with('itemPesanans.produk')->findOrFail($id);
        return view('admin.show_pesanan', compact('pesanan'));
    }

    /**
     * Menampilkan formulir untuk mengedit pesanan tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $pesanan = Pesanan::with('itemPesanans.produk')->findOrFail($id);
        $produks = Produk::where('is_active', true)->get();
        return view('admin.edit_pesanan', compact('pesanan', 'produks'));
    }

    /**
     * Memperbarui pesanan tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'required|string|max:20',
            'tanggal_pengiriman' => 'required|date',
            'alamat_pengiriman' => 'required|string',
            'waktu_pengiriman' => 'nullable|string|max:50',
            'catatan_khusus' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        $totalHargaPesanan = 0;
        $newItems = [];

        foreach ($request->input('items') as $item) {
            $produk = Produk::find($item['produk_id']);

            if ($produk && $produk->is_active) {
                $hargaSatuan = $produk->harga_jual;
                $jumlahPorsi = $item['jumlah'];
                $subtotalItem = $hargaSatuan * $jumlahPorsi;

                $totalHargaPesanan += $subtotalItem;

                $newItem = [
                    'produk_id' => $produk->id,
                    'jumlah_porsi' => $jumlahPorsi,
                    'harga_satuan_saat_pesan' => $hargaSatuan,
                    'subtotal_item' => $subtotalItem,
                ];
                $newItems[] = $newItem;
            } else {
                return redirect()->back()->withErrors(['items' => 'Salah satu produk yang dipilih tidak valid atau tidak aktif.'])->withInput();
            }
        }

        $pesanan->update([
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
            'waktu_pengiriman' => $request->waktu_pengiriman,
            'nama_pelanggan' => $request->nama_pelanggan,
            'telepon_pelanggan' => $request->telepon_pelanggan,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'catatan_khusus' => $request->catatan_khusus,
            'total_harga' => $totalHargaPesanan,
        ]);

        $pesanan->itemPesanans()->delete();
        foreach ($newItems as $itemData) {
            $pesanan->itemPesanans()->create($itemData);
        }

        return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Pesanan berhasil diperbarui!');
    }

    /**
     * Menghapus pesanan tertentu dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->itemPesanans()->delete();
        $pesanan->delete();

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil dihapus!');
    }

    /**
     * Memperbarui status pesanan melalui permintaan AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, int $id)
    {
        // Validasi request: pastikan status yang dikirim valid
        $request->validate([
            'status' => 'required|string|in:pending,diproses,dikirim,selesai,dibatalkan',
        ]);

        try {
            $pesanan = Pesanan::findOrFail($id);
            $pesanan->status_pesanan = $request->status;
            $pesanan->save();

            // Kembalikan respons JSON sukses
            return response()->json(['message' => 'Status pesanan berhasil diperbarui!', 'new_status' => $pesanan->status_pesanan], 200);

        } catch (\Exception $e) {
            // Kembalikan respons JSON error
            return response()->json(['message' => 'Gagal memperbarui status pesanan.', 'error' => $e->getMessage()], 500);
        }
    }
}