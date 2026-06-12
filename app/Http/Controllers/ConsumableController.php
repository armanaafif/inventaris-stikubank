<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConsumableService;
use App\Models\Consumable;
use App\Models\ConsumableTransaction;
use App\Models\UnitMeasure;
use App\Models\StockRequest;
use App\Models\Borrowing; // <-- TAMBAHKAN IMPORT INI

class ConsumableController extends Controller
{
    protected $service;

    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    /**
     * --------------------------------------------------------------------------
     * Daftar Barang
     * --------------------------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = Consumable::with('unitMeasure');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->paginate(10)->withQueryString();

        foreach ($data as $item) {
            $item->stock = $this->service->getStock($item->id);
        }

        return view('barang.list', compact('data'));
    }

    /**
     * --------------------------------------------------------------------------
     * Halaman Tambah Barang
     * --------------------------------------------------------------------------
     */
    public function create()
    {
        $units = UnitMeasure::latest()->get();
        return view('barang.create', compact('units'));
    }

    /**
     * --------------------------------------------------------------------------
     * Simpan Barang
     * --------------------------------------------------------------------------
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_measure_id' => 'required|exists:unit_measures,id',
            'minimum_stock' => 'required|integer|min:0',
            'initial_stock' => 'required|integer|min:0',
            'condition' => 'required|in:BARU,BEKAS,LAYAK,RUSAK',
            'status' => 'required|in:AKTIF,NONAKTIF'
        ]);

        // Jika user adalah admin, langsung buat barang
        if (auth()->user()->role === 'admin') {
            $item = Consumable::create([
                'name' => $request->name,
                'unit_measure_id' => $request->unit_measure_id,
                'minimum_stock' => $request->minimum_stock,
                'condition' => $request->condition,
                'status' => $request->status
            ]);

            if ($request->initial_stock > 0) {
                $this->service->addStock($item->id, $request->initial_stock, 'Stok awal barang');
            }

            return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
        }

        // Jika bukan admin, buat request approval
        StockRequest::create([
            'request_type' => 'CREATE_ITEM',
            'user_id' => auth()->id(),
            'item_name' => $request->name,
            'unit_measure_id' => $request->unit_measure_id,
            'minimum_stock' => $request->minimum_stock,
            'initial_stock' => $request->initial_stock,
            'condition' => $request->condition,
            'item_status' => $request->status,
            'status' => 'pending'
        ]);

        return redirect()->route('barang.index')->with('success', 'Request penambahan barang berhasil dikirim dan menunggu approval admin.');
    }

    /**
     * --------------------------------------------------------------------------
     * Detail Barang
     * --------------------------------------------------------------------------
     */
    public function show(Request $request, $id)
    {
        $item = Consumable::with('unitMeasure')->findOrFail($id);
        $query = $item->transactions();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->get();
        $stock = $this->service->getStock($id);

        return view('barang.detail', compact('item', 'stock', 'transactions'));
    }

    /**
     * --------------------------------------------------------------------------
     * Hapus Barang
     * --------------------------------------------------------------------------
     */
    public function destroy($id)
    {
        $item = Consumable::findOrFail($id);
        $namaBarang = $item->name;
        $transaksiCount = ConsumableTransaction::where('consumable_id', $id)->count();

        try {
            // Hapus semua transaksi terkait terlebih dahulu
            ConsumableTransaction::where('consumable_id', $id)->delete();
            
            // Hapus barang
            $item->delete();

            $message = 'Barang "' . $namaBarang . '" berhasil dihapus';
            if ($transaksiCount > 0) {
                $message .= ' beserta ' . $transaksiCount . ' riwayat transaksinya';
            }

            return redirect()->route('barang.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('barang.index')->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }

    /**
     * --------------------------------------------------------------------------
     * Monitoring Stock
     * --------------------------------------------------------------------------
     */
    public function stock()
    {
        $data = Consumable::with('unitMeasure')->latest()->get();

        foreach ($data as $item) {
            $item->stock = $this->service->getStock($item->id);
        }

        return view('barang.stock', compact('data'));
    }

    /**
     * --------------------------------------------------------------------------
     * Histori Transaksi
     * --------------------------------------------------------------------------
     */
    public function history(Request $request)
    {
        $query = ConsumableTransaction::with(['consumable', 'consumable.unitMeasure']);

        if ($request->search) {
            $query->whereHas('consumable', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();
        $barangMasuk = ConsumableTransaction::where('type', 'IN')->sum('quantity');
        $barangKeluar = ConsumableTransaction::where('type', 'OUT')->sum('quantity');
        $totalTransaksi = ConsumableTransaction::count();
        $totalBarang = Consumable::count();

        // Data grafik 6 bulan terakhir
        $months = collect(range(5, 0))->map(function($i) {
            return now()->subMonths($i);
        });

        $chartLabels = [];
        $barangMasukData = [];
        $barangKeluarData = [];

        foreach ($months as $month) {
            $startDate = $month->copy()->startOfMonth();
            $endDate = $month->copy()->endOfMonth();

            $chartLabels[] = $month->format('M Y');
            $barangMasukData[] = ConsumableTransaction::where('type', 'IN')->whereBetween('created_at', [$startDate, $endDate])->sum('quantity');
            $barangKeluarData[] = ConsumableTransaction::where('type', 'OUT')->whereBetween('created_at', [$startDate, $endDate])->sum('quantity');
        }

        // Top 5 barang paling sering dipakai
        $topUsedItems = Consumable::with('unitMeasure')->get()->map(function($item) {
            $item->total_keluar = ConsumableTransaction::where('consumable_id', $item->id)->where('type', 'OUT')->sum('quantity');
            return $item;
        })->sortByDesc('total_keluar')->take(5)->values();

        $topUsedLabels = $topUsedItems->pluck('name')->toArray();
        $topUsedData = $topUsedItems->pluck('total_keluar')->toArray();

        // Top 5 barang paling sering masuk
        $topInItems = Consumable::with('unitMeasure')->get()->map(function($item) {
            $item->total_masuk = ConsumableTransaction::where('consumable_id', $item->id)->where('type', 'IN')->sum('quantity');
            return $item;
        })->sortByDesc('total_masuk')->take(5)->values();

        $topInLabels = $topInItems->pluck('name')->toArray();
        $topInData = $topInItems->pluck('total_masuk')->toArray();

        return view('history.index', compact(
            'transactions', 'barangMasuk', 'barangKeluar', 'totalTransaksi', 'totalBarang',
            'chartLabels', 'barangMasukData', 'barangKeluarData',
            'topUsedLabels', 'topUsedData', 'topInLabels', 'topInData'
        ));
    }

    /**
     * --------------------------------------------------------------------------
     * Tambah Stock
     * --------------------------------------------------------------------------
     */
    public function addStock(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|exists:consumables,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        // Jika user adalah admin, proses langsung
        if (auth()->user()->role === 'admin') {
            $this->service->addStock($request->consumable_id, $request->quantity, $request->note);
            return redirect()->back()->with('success', 'Stock berhasil ditambahkan');
        }

        // Jika bukan admin, buat request approval
        StockRequest::create([
            'consumable_id' => $request->consumable_id,
            'quantity' => $request->quantity,
            'type' => 'IN',
            'note' => $request->note,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Request tambah stok berhasil dikirim dan menunggu approval admin.');
    }

    /**
     * --------------------------------------------------------------------------
     * Gunakan Barang
     * --------------------------------------------------------------------------
     */
    public function takeStock(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|exists:consumables,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        // Jika user adalah admin, proses langsung
        if (auth()->user()->role === 'admin') {
            try {
                $this->service->takeStock($request->consumable_id, $request->quantity, $request->note);
                return redirect()->back()->with('success', 'Barang berhasil digunakan');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        // Jika bukan admin, buat request approval
        StockRequest::create([
            'consumable_id' => $request->consumable_id,
            'quantity' => $request->quantity,
            'type' => 'OUT',
            'note' => $request->note,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Request penggunaan barang berhasil dikirim dan menunggu approval admin.');
    }

    /**
     * --------------------------------------------------------------------------
     * Peminjaman Barang
     * --------------------------------------------------------------------------
     * Method untuk melakukan peminjaman barang
     * Status awal: PENDING (menunggu approval admin)
     */
    public function borrowItem(Request $request)
    {
        // Validasi input dari form peminjaman
        $request->validate([
            'consumable_id' => 'required|exists:consumables,id',
            'borrower_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'return_date' => 'required|date|after:today',
            'note' => 'nullable|string|max:500'
        ]);

        // Cek stok barang tersedia
        $currentStock = $this->service->getStock($request->consumable_id);
        
        if ($currentStock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . number_format($currentStock));
        }

        // Simpan data peminjaman ke tabel borrowings
        $borrowing = Borrowing::create([
            'consumable_id' => $request->consumable_id,
            'user_id' => auth()->id(), // ID user yang meminjam
            'borrower_name' => $request->borrower_name,
            'quantity' => $request->quantity,
            'borrow_date' => now(), // Tanggal pinjam = sekarang
            'return_date' => $request->return_date,
            'note' => $request->note,
            'status' => 'PENDING' // Status awal menunggu approval admin
        ]);

        // Jika berhasil disimpan, redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Peminjaman barang berhasil diajukan! Menunggu persetujuan admin.');
    }

    /**
     * --------------------------------------------------------------------------
     * API Stock
     * --------------------------------------------------------------------------
     */
    public function getStock($id)
    {
        $stock = $this->service->getStock($id);
        return response()->json(['stock' => $stock]);
    }
}
