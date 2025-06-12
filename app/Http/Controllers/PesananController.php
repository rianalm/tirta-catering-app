<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\ItemPesanan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $searchQuery = $request->query('search');
        $tanggalFilter = $request->query('filter_tanggal_pengiriman'); 

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

        if ($tanggalFilter) {
            $query->whereDate('tanggal_pengiriman', $tanggalFilter)
                  ->orderBy('waktu_pengiriman', 'asc'); 
        } else {
            $query->orderBy('tanggal_pengiriman', 'desc')->orderBy('id', 'desc'); 
        }

        $pesanans = $query->paginate(10);
        $allStatuses = ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];

        return view('admin.index_pesanan', compact('pesanans', 'statusFilter', 'searchQuery', 'allStatuses', 'tanggalFilter'));
    }

    public function create()
    {
        $produks = Produk::where('is_active', true)->orderBy('nama_produk')->get(); 
        return view('admin.create_pesanan', compact('produks'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'required|string|max:20',
            'tanggal_pengiriman' => 'required|date',
            'alamat_pengiriman' => 'required|string',
            'waktu_pengiriman' => 'nullable|date_format:H:i',
            'jenis_penyajian' => 'nullable|string|max:50', // <-- VALIDASI BARU
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
        
        $statusAwal = 'pending';
        $tanggalPengirimanInput = Carbon::parse($request->tanggal_pengiriman); 
        if ($tanggalPengirimanInput->isSameDay(Carbon::today())) { 
            $statusAwal = 'diproses';
        }
        
        $pesanan = Pesanan::create([
            'tanggal_pesanan' => Carbon::now(),
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
            'waktu_pengiriman' => $request->waktu_pengiriman,
            'nama_pelanggan' => $request->nama_pelanggan,
            'telepon_pelanggan' => $request->telepon_pelanggan,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'catatan_khusus' => $request->catatan_khusus,
            'jenis_penyajian' => $request->jenis_penyajian, // <-- SIMPAN DATA BARU
            'status_pesanan' => $statusAwal, 
            'total_harga' => $totalHargaPesanan,
        ]);

        $pesanan->itemPesanans()->createMany($itemsToStore);

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil ditambahkan! Status: ' . ucfirst($statusAwal));
    }
    
    public function show(int $id)
    {
        $pesanan = Pesanan::with('itemPesanans.produk')->findOrFail($id);
        return view('admin.show_pesanan', compact('pesanan'));
    }
    
    public function edit(int $id)
    {
        $pesanan = Pesanan::with('itemPesanans.produk')->findOrFail($id);
        if (in_array($pesanan->status_pesanan, ['selesai', 'dibatalkan'])) {
            return redirect()->route('admin.pesanan.show', $pesanan->id)
                             ->with('error', 'Pesanan yang statusnya "' . $pesanan->status_pesanan . '" tidak dapat diedit lagi.');
        }
        $produks = Produk::where('is_active', true)->orderBy('nama_produk')->get();
        return view('admin.edit_pesanan', compact('pesanan', 'produks'));
    }
    
    public function update(Request $request, int $id)
    {
        $pesanan = Pesanan::findOrFail($id); 
        
        if (in_array($pesanan->status_pesanan, ['selesai', 'dibatalkan'])) {
            return redirect()->route('admin.pesanan.show', $pesanan->id)
                             ->with('error', 'Gagal memperbarui. Pesanan yang statusnya "' . $pesanan->status_pesanan . '" tidak dapat diedit lagi.');
        }

        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'required|string|max:20',
            'tanggal_pengiriman' => 'required|date',
            'alamat_pengiriman' => 'required|string',
            'waktu_pengiriman' => 'nullable|date_format:H:i',
            'jenis_penyajian' => 'nullable|string|max:50', // <-- VALIDASI BARU
            'catatan_khusus' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);
        
        $statusSaatIni = $pesanan->status_pesanan;
        $tanggalPengirimanSaatIniDiDB = Carbon::parse($pesanan->tanggal_pengiriman);
        
        $totalHargaPesanan = 0;
        $newItems = [];
        foreach ($request->input('items') as $item) {
            $produk = Produk::find($item['produk_id']);
            if ($produk && $produk->is_active) {
                $hargaSatuan = $produk->harga_jual;
                $jumlahPorsi = $item['jumlah'];
                $subtotalItem = $hargaSatuan * $jumlahPorsi;
                $totalHargaPesanan += $subtotalItem;
                $newItems[] = [
                    'produk_id' => $produk->id,
                    'jumlah_porsi' => $jumlahPorsi,
                    'harga_satuan_saat_pesan' => $hargaSatuan,
                    'subtotal_item' => $subtotalItem,
                ];
            } else {
                return redirect()->back()->withErrors(['items' => 'Salah satu produk yang dipilih tidak valid atau tidak aktif.'])->withInput();
            }
        }
        
        $dataToUpdate = [
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
            'waktu_pengiriman' => $request->waktu_pengiriman,
            'nama_pelanggan' => $request->nama_pelanggan,
            'telepon_pelanggan' => $request->telepon_pelanggan,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'catatan_khusus' => $request->catatan_khusus,
            'jenis_penyajian' => $request->jenis_penyajian, // <-- SIMPAN DATA BARU
            'total_harga' => $totalHargaPesanan,
        ];
        
        $newTanggalPengirimanDariRequest = Carbon::parse($request->tanggal_pengiriman);
        $statusPotensialBaru = $statusSaatIni;

        if ($newTanggalPengirimanDariRequest->isSameDay(Carbon::today())) {
            if ($statusSaatIni === 'pending') {
                $statusPotensialBaru = 'diproses';
            }
        } elseif ($newTanggalPengirimanDariRequest->isFuture()) {
            if ($statusSaatIni === 'diproses' && $tanggalPengirimanSaatIniDiDB->isSameDay(Carbon::today())) {
                $statusPotensialBaru = 'pending';
            }
        }
        
        if ($statusPotensialBaru !== $statusSaatIni) {
            $dataToUpdate['status_pesanan'] = $statusPotensialBaru;
        }

        $pesanan->update($dataToUpdate);
        
        $pesanan->itemPesanans()->delete();
        foreach ($newItems as $itemData) {
            $pesanan->itemPesanans()->create($itemData);
        }

        return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->itemPesanans()->delete(); 
        $pesanan->delete();
        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil dihapus!');
    }
    public function updateStatus(Request $request, int $id)
    {
        $validStatuses = ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];
        $request->validate([
            'status' => 'required|string|in:' . implode(',', $validStatuses),
        ]);
        try {
            $pesanan = Pesanan::findOrFail($id);
            $statusLama = $pesanan->status_pesanan;
            $statusBaru = $request->status;
            if ($statusLama == $statusBaru) {
                return response()->json([
                    'message' => 'Status pesanan tidak berubah (sudah ' . $statusBaru . ').', 
                    'new_status' => $pesanan->status_pesanan
                ], 200);
            }
            $allowedTransitions = [
                'pending'    => ['diproses', 'dibatalkan'],
                'diproses'   => ['dikirim', 'dibatalkan', 'pending'], 
                'dikirim'    => ['selesai', 'dibatalkan', 'diproses'], 
                'selesai'    => [], 
                'dibatalkan' => [], 
            ];
            if (!isset($allowedTransitions[$statusLama]) || !in_array($statusBaru, $allowedTransitions[$statusLama])) {
                return response()->json([
                    'message' => "Perubahan status dari '{$statusLama}' ke '{$statusBaru}' tidak diizinkan."
                ], 422); 
            }
            $pesanan->status_pesanan = $statusBaru;
            $pesanan->save();
            return response()->json([
                'message' => 'Status pesanan berhasil diperbarui!', 
                'new_status' => $pesanan->status_pesanan
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui status pesanan karena kesalahan server.'], 500);
        }
    }
    public function operasionalIndex(Request $request)
    {
        $tanggalFilter = $request->query('filter_tanggal_pengiriman', Carbon::today()->toDateString());

        $query = Pesanan::with(['itemPesanans', 'itemPesanans.produk'])
                        ->whereDate('tanggal_pengiriman', $tanggalFilter)
                        ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
                        ->orderBy('waktu_pengiriman', 'asc');

        $pesanans = $query->get();

        // --- PERUBAHAN DI SINI ---
        // Menggunakan path view yang baru dengan notasi titik.
        return view('admin.pesanan_operasional.index', compact('pesanans', 'tanggalFilter'));
    }
    public function operasionalShow(Pesanan $pesanan)
    {
        // Kita bisa langsung mengirim data pesanan ke view baru
        // Eager load relasi jika belum ter-load
        $pesanan->load(['itemPesanans', 'itemPesanans.produk']);

        return view('admin.pesanan_operasional.show', compact('pesanan'));
    }

    public function generateWorksheetPdf(Pesanan $pesanan)
    {
        // Pastikan relasi sudah ter-load
        $pesanan->load(['itemPesanans', 'itemPesanans.produk']);

        // Render view khusus PDF dengan data pesanan
        $pdf = PDF::loadView('admin.pesanan_operasional.pdf_worksheet', compact('pesanan'));

        // Tampilkan PDF di browser (stream) atau paksa unduh (download)
        // Kita gunakan stream agar bisa dilihat dulu sebelum di-save
        return $pdf->stream('worksheet-pesanan-'.$pesanan->id.'.pdf');
    }

    // --- METHOD BARU UNTUK UPDATE BIAYA INVOICE ---
    public function editInvoice(Pesanan $pesanan)
    {
        return view('admin.pesanan.invoice_edit', compact('pesanan'));
    }

    // METHOD BARU UNTUK MENYIMPAN DATA INVOICE
    public function updateInvoice(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'ongkir'       => ['nullable', 'numeric', 'min:0'],
            'biaya_lain'   => ['nullable', 'numeric', 'min:0'],
            'pajak_persen' => ['nullable', 'numeric', 'min:0'], // Validasi persentase pajak
        ]);

        $subtotal = $pesanan->total_harga;
        $ongkir = $request->ongkir ?? 0;
        $biaya_lain = $request->biaya_lain ?? 0;
        $pajak_persen = $request->pajak_persen ?? 0;

        // Hitung nominal pajak dari subtotal
        $nominal_pajak = ($subtotal * $pajak_persen) / 100;

        // Hitung grand total
        $grand_total = $subtotal + $nominal_pajak + $ongkir + $biaya_lain;

        // Update data pesanan di database
        $pesanan->update([
            'ongkir'       => $ongkir,
            'biaya_lain'   => $biaya_lain,
            'pajak_persen' => $pajak_persen,
            'pajak'        => $nominal_pajak, // Simpan hasil perhitungannya
            'grand_total'  => $grand_total,
        ]);

        return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Rincian biaya invoice berhasil diperbarui.');
    }

    public function generateInvoicePdf(Pesanan $pesanan)
    {
        // Pastikan semua relasi data yang dibutuhkan sudah ter-load
        $pesanan->load(['itemPesanans', 'itemPesanans.produk']);

        // Data perusahaan Anda, bisa juga diambil dari database atau file config
        $companyInfo = [
            'name' => 'Tirta Catering',
            'address' => 'Jl. Kh Hasyim Ashari No. 27, Buaran Indah, Kota Tangerang',
            'phone' => '0812-1206-9998',
            'email' => 'tirtacatering.99@gmail.com',
            'rekening' => 'Bank BCC - 1234567890 a/n Tirta Catering'
        ];

        // Render view khusus PDF dengan data yang diperlukan
        $pdf = PDF::loadView('admin.pesanan.pdf_invoice', compact('pesanan', 'companyInfo'));

        // Tampilkan PDF di browser
        return $pdf->stream('invoice-'.$pesanan->id.'-'.$pesanan->nama_pelanggan.'.pdf');
    }
} 