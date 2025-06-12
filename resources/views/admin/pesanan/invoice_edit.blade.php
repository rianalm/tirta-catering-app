@extends('layouts.admin')

@section('title', 'Edit Invoice untuk Pesanan #' . $pesanan->id)

@push('styles')
<style>
    .invoice-form-container { max-width: 700px; margin: 0 auto; }
    .invoice-summary { background-color: #f8f9fa; border: 1px solid #e9ecef; padding: 20px; margin-bottom: 25px; border-radius: 8px; }
    .price-item { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 1.1em; }
    .price-item.grand-total { font-weight: bold; font-size: 1.4em; color: #28a745; border-top: 2px solid #ddd; padding-top: 15px; margin-top: 15px;}
    .form-group label { font-weight: 600; }
    .tax-display { font-style: italic; color: #555; font-size: 0.9em; }
</style>
@endpush

@section('content')
<div class="container-content">
    <div class="content-header">
        <h1>Lengkapi Invoice untuk Pesanan #{{ $pesanan->id }}</h1>
        <p>Pelanggan: <strong>{{ $pesanan->nama_pelanggan }}</strong></p>
    </div>

    @if ($errors->any())
        {{-- ... (Blok error) ... --}}
    @endif

    <form action="{{ route('admin.pesanan.invoice.update', $pesanan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="invoice-summary">
            <div class="price-item">
                <span>Subtotal Item:</span>
                <strong id="subtotal-value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
            </div>
            <hr>

            {{-- Input Ongkir dan Biaya Lain (sebelum Pajak) --}}
            <div class="form-group mt-3">
                <label for="ongkir">Ongkos Kirim:</label>
                <input type="number" id="ongkir" name="ongkir" value="{{ old('ongkir', $pesanan->ongkir) }}" step="1" min="0" class="form-control price-input">
            </div>
            <div class="form-group">
                <label for="biaya_lain">Service Charge / Biaya Lain:</label>
                <input type="number" id="biaya_lain" name="biaya_lain" value="{{ old('biaya_lain', $pesanan->biaya_lain) }}" step="1" min="0" class="form-control price-input">
            </div>

            {{-- Dropdown Pilihan Pajak (BARU) --}}
            <div class="form-group">
                <label for="pajak_persen">Jenis Pajak:</label>
                <select name="pajak_persen" id="pajak_persen" class="form-control price-input">
                    <option value="0" {{ old('pajak_persen', $pesanan->pajak_persen) == 0 ? 'selected' : '' }}>Tidak Ada Pajak</option>
                    <option value="11" {{ old('pajak_persen', $pesanan->pajak_persen) == 11 ? 'selected' : '' }}>PPN (11%)</option>
                    {{-- Tambahkan opsi pajak lain jika perlu --}}
                </select>
            </div>
            
            {{-- Tampilan Nominal Pajak yang Dihitung Otomatis --}}
            <div class="price-item">
                <span>Nominal Pajak:</span>
                <span id="pajak-display" class="tax-display">Rp 0</span>
            </div>
            
            {{-- Tampilan Grand Total --}}
            <div class="price-item grand-total">
                <span>GRAND TOTAL</span>
                <span id="grand-total-display">Rp 0</span>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Simpan Rincian Invoice</button>
            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil nilai subtotal dari PHP (lebih aman daripada dari teks)
    const subtotal = {{ $pesanan->total_harga }};
    
    // Ambil elemen-elemen form
    const ongkirInput = document.getElementById('ongkir');
    const biayaLainInput = document.getElementById('biaya_lain');
    const pajakPersenSelect = document.getElementById('pajak_persen');
    
    // Ambil elemen-elemen display
    const pajakDisplay = document.getElementById('pajak-display');
    const grandTotalDisplay = document.getElementById('grand-total-display');

    function calculateInvoice() {
        const ongkir = parseFloat(ongkirInput.value) || 0;
        const biayaLain = parseFloat(biayaLainInput.value) || 0;
        const pajakPersen = parseFloat(pajakPersenSelect.value) || 0;

        // Hitung nominal pajak dari subtotal
        const nominalPajak = (subtotal * pajakPersen) / 100;
        
        // Hitung grand total
        const grandTotal = subtotal + nominalPajak + ongkir + biayaLain;
        
        // Format angka ke format Rupiah
        const formatter = new Intl.NumberFormat('id-ID');

        // Update tampilan
        pajakDisplay.textContent = `Rp ${formatter.format(nominalPajak)}`;
        grandTotalDisplay.textContent = `Rp ${formatter.format(grandTotal)}`;
    }

    // Tambahkan event listener ke semua input yang mempengaruhi kalkulasi
    document.querySelectorAll('.price-input').forEach(input => {
        input.addEventListener('input', calculateInvoice);
    });

    // Panggil sekali saat halaman dimuat untuk menampilkan total awal yang benar
    calculateInvoice();
});
</script>
@endpush