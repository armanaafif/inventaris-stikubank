<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\ConsumableStock;
use App\Models\ConsumableTransaction;
use App\Models\StockRequest;
use App\Models\Borrowing;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard
     * 
     * Semua data statistik dikumpulkan di sini lalu dikirim ke view.
     * Data yang ditampilkan meliputi:
     * - Statistik barang dan stok
     * - Grafik transaksi 6 bulan terakhir
     * - Top 5 barang paling aktif
     * - Statistik peminjaman (baru)
     */
    public function index()
    {
        // ============================================================
        // BAGIAN 1: STATISTIK DASAR BARANG
        // ============================================================

        // Menghitung jumlah total barang yang terdaftar di database
        $totalBarang = Consumable::count();

        $totalStock = ConsumableStock::sum('quantity');
        $totalLokasi = \App\Models\Location::where('is_active', true)->count();
        $barangMasukHariIni = ConsumableTransaction::where('type', 'IN')
            ->whereDate('created_at', today())
            ->sum('quantity');
        $barangKeluarHariIni = ConsumableTransaction::where('type', 'OUT')
            ->whereDate('created_at', today())
            ->sum('quantity');
        $ringkasanInventaris = Consumable::with(['unitMeasure', 'stocks'])
            ->get()
            ->groupBy(fn ($item) => $item->unitMeasure->name ?? '-')
            ->map(fn ($items) => $items->sum(fn ($item) => $item->stocks->sum('quantity')))
            ->sortKeys();

        // Menghitung berapa banyak request penambahan barang yang masih pending
        $pendingRequest = StockRequest::where('status', 'pending')->count();

        // ============================================================
        // BAGIAN 2: IDENTIFIKASI BARANG YANG STOKNYA MENIPIS
        // ============================================================
        
        $barangMenipis = 0;
        $items = Consumable::with('stocks')->get();

        // Loop setiap barang, hitung stoknya, bandingkan dengan minimum stok
        foreach ($items as $item) {
            $stock = $item->stocks->sum('quantity');

            if (!is_null($item->minimum_stock) && $stock <= $item->minimum_stock) {
                $barangMenipis++;
            }
        }

        // ============================================================
        // BAGIAN 3: AKTIVITAS TERBARU
        // ============================================================
        
        // Ambil 5 transaksi terakhir untuk ditampilkan di dashboard
        $recentTransactions = ConsumableTransaction::with([
            'consumable',
            'consumable.unitMeasure'
        ])
        ->latest()
        ->take(5)
        ->get();

        // ============================================================
        // BAGIAN 4: REKAP TOTAL BARANG MASUK DAN KELUAR
        // ============================================================
        
        // Jumlah semua barang yang pernah masuk
        $barangMasuk = ConsumableTransaction::where('type', 'IN')->sum('quantity');
        
        // Jumlah semua barang yang pernah keluar
        $barangKeluar = ConsumableTransaction::where('type', 'OUT')->sum('quantity');
        
        // Total transaksi yang pernah terjadi
        $totalTransaksi = ConsumableTransaction::count();

        // ============================================================
        // BAGIAN 5: GRAFIK TRANSAKSI 6 BULAN TERAKHIR
        // ============================================================
        
        // Buat daftar 6 bulan kebelakang dari sekarang
        $months = collect(range(5, 0))->map(function($i) {
            return now()->subMonths($i);
        });

        $chartLabels = [];       // Label bulan (contoh: Jan 2025, Feb 2025)
        $barangMasukData = [];   // Data barang masuk per bulan
        $barangKeluarData = [];  // Data barang keluar per bulan

        // Loop setiap bulan, hitung jumlah transaksi masuk dan keluar
        foreach ($months as $month) {
            $startDate = $month->copy()->startOfMonth();
            $endDate = $month->copy()->endOfMonth();

            $chartLabels[] = $month->format('M Y');

            $barangMasukData[] = (int) ConsumableTransaction::where('type', 'IN')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            $barangKeluarData[] = (int) ConsumableTransaction::where('type', 'OUT')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');
        }

        // ============================================================
        // BAGIAN 6: TOP 5 BARANG PALING SERING DIPAKAI (KELUAR)
        // ============================================================
        
        $topUsedItems = Consumable::with('unitMeasure')
            ->get()
            ->map(function($item) {
                // Hitung total barang keluar untuk setiap item
                $keluar = (int) ConsumableTransaction::where('consumable_id', $item->id)
                    ->where('type', 'OUT')
                    ->sum('quantity');
                $item->total_keluar = $keluar;
                return $item;
            })
            ->sortByDesc('total_keluar')  // Urutkan dari yang terbanyak
            ->take(5)                      // Ambil 5 teratas
            ->values();

        $topUsedLabels = $topUsedItems->pluck('name')->toArray();
        $topUsedData = $topUsedItems->pluck('total_keluar')->toArray();

        // ============================================================
        // BAGIAN 7: TOP 5 BARANG PALING SERING MASUK
        // ============================================================
        
        $topInItems = Consumable::with('unitMeasure')
            ->get()
            ->map(function($item) {
                // Hitung total barang masuk untuk setiap item
                $masuk = (int) ConsumableTransaction::where('consumable_id', $item->id)
                    ->where('type', 'IN')
                    ->sum('quantity');
                $item->total_masuk = $masuk;
                return $item;
            })
            ->sortByDesc('total_masuk')
            ->take(5)
            ->values();

        $topInLabels = $topInItems->pluck('name')->toArray();
        $topInData = $topInItems->pluck('total_masuk')->toArray();

        // ============================================================
        // BAGIAN 8: STATISTIK PEMINJAMAN (FITUR BARU)
        // ============================================================
        
        // Berapa banyak barang yang sedang dipinjam saat ini
        $totalBorrowed = Borrowing::where('status', 'BORROWED')->count();

        // Berapa banyak pengajuan pinjam yang masih menunggu persetujuan admin
        $pendingBorrowings = Borrowing::where('status', 'PENDING')->count();

        // Berapa banyak peminjaman yang melebihi tanggal kembali yang dijanjikan
        $lateBorrowings = Borrowing::where('status', 'BORROWED')
            ->whereDate('return_date', '<', now())
            ->count();

        // ============================================================
        // BAGIAN 9: KIRIM SEMUA DATA KE VIEW
        // ============================================================
        
        return view('dashboard', compact(
            // Statistik dasar
            'totalBarang',
            'totalStock',
            'totalLokasi',
            'barangMasukHariIni',
            'barangKeluarHariIni',
            'ringkasanInventaris',
            'pendingRequest',
            'barangMenipis',
            
            // Aktivitas
            'recentTransactions',
            'barangMasuk',
            'barangKeluar',
            'totalTransaksi',
            
            // Data grafik
            'chartLabels',
            'barangMasukData',
            'barangKeluarData',
            
            // Top 5 barang
            'topUsedLabels',
            'topUsedData',
            'topInLabels',
            'topInData',
            
            // Statistik peminjaman (baru)
            'totalBorrowed',
            'pendingBorrowings',
            'lateBorrowings'
        ));
    }
}
