<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConsumableService;
use App\Models\Consumable;
use App\Models\ConsumableTransaction;
use App\Models\UnitMeasure;

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

        /*
        |--------------------------------------------------------------------------
        | Search Barang
        |--------------------------------------------------------------------------
        */

        if ($request->search) {

            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Filter Kondisi
        |--------------------------------------------------------------------------
        */

        if ($request->condition) {

            $query->where(
                'condition',
                $request->condition
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->status) {

            $query->where(
                'status',
                $request->status
            );

        }

        $data = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Hitung Stock
        |--------------------------------------------------------------------------
        */

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

            /*
            |--------------------------------------------------------------------------
            | Informasi Barang
            |--------------------------------------------------------------------------
            */

            'name' => 'required|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | Relasi
            |--------------------------------------------------------------------------
            */

            'unit_measure_id' => 'required|exists:unit_measures,id',

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'minimum_stock' => 'required|integer|min:0',

            'initial_stock' => 'required|integer|min:0',

            /*
            |--------------------------------------------------------------------------
            | Kondisi & Status
            |--------------------------------------------------------------------------
            */

            'condition' => 'required|in:BARU,BEKAS,LAYAK,RUSAK',

            'status' => 'required|in:AKTIF,NONAKTIF'

        ]);

        /*
        |--------------------------------------------------------------------------
        | Simpan Barang
        |--------------------------------------------------------------------------
        */

        $item = Consumable::create([

            'name' => $request->name,

            'unit_measure_id' => $request->unit_measure_id,

            'minimum_stock' => $request->minimum_stock,

            /*
            |--------------------------------------------------------------------------
            | Kondisi & Status
            |--------------------------------------------------------------------------
            */

            'condition' => $request->condition,

            'status' => $request->status

        ]);

        /*
        |--------------------------------------------------------------------------
        | Tambah Stock Awal
        |--------------------------------------------------------------------------
        */

        if ($request->initial_stock > 0) {

            $this->service->addStock(

                $item->id,

                $request->initial_stock,

                'Stok awal barang'

            );

        }

        return redirect('/barang')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * --------------------------------------------------------------------------
     * Detail Barang
     * --------------------------------------------------------------------------
     */
    public function show(Request $request, $id)
    {
        $item = Consumable::with('unitMeasure')
            ->findOrFail($id);

        $query = $item->transactions();

        /*
        |--------------------------------------------------------------------------
        | Filter Transaksi
        |--------------------------------------------------------------------------
        */

        if ($request->type) {

            $query->where('type', $request->type);

        }

        $transactions = $query
            ->latest()
            ->get();

        $stock = $this->service->getStock($id);

        return view('barang.detail', compact(
            'item',
            'stock',
            'transactions'
        ));
    }

    /**
     * --------------------------------------------------------------------------
     * Monitoring Stock
     * --------------------------------------------------------------------------
     */
    public function stock()
    {
        $data = Consumable::with('unitMeasure')
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Hitung Stock
        |--------------------------------------------------------------------------
        */

        foreach ($data as $item) {

            $item->stock = $this->service->getStock($item->id);

        }

        return view('barang.stock', compact('data'));
    }

    /**
     * --------------------------------------------------------------------------
     * Histori Transaksi & Analisis Grafik
     * --------------------------------------------------------------------------
     */
    public function history(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Query Transaksi untuk Tabel
        |--------------------------------------------------------------------------
        */

        $query = ConsumableTransaction::with([
            'consumable',
            'consumable.unitMeasure'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search Barang
        |--------------------------------------------------------------------------
        */

        if ($request->search) {

            $query->whereHas('consumable', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Filter Type
        |--------------------------------------------------------------------------
        */

        if ($request->type) {

            $query->where('type', $request->type);

        }

        /*
        |--------------------------------------------------------------------------
        | Data Transaksi (Pagination)
        |--------------------------------------------------------------------------
        */

        $transactions = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistik Overview (Card)
        |--------------------------------------------------------------------------
        */

        $barangMasuk = ConsumableTransaction::where('type', 'IN')
            ->sum('quantity');

        $barangKeluar = ConsumableTransaction::where('type', 'OUT')
            ->sum('quantity');

        $totalTransaksi = ConsumableTransaction::count();

        $totalBarang = Consumable::count();

        /*
        |--------------------------------------------------------------------------
        | Data untuk Grafik (6 Bulan Terakhir)
        |--------------------------------------------------------------------------
        */

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

            $barangMasukData[] = ConsumableTransaction::where('type', 'IN')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            $barangKeluarData[] = ConsumableTransaction::where('type', 'OUT')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');
        }

        /*
        |--------------------------------------------------------------------------
        | Top 5 Barang Paling Sering Dipakai
        |--------------------------------------------------------------------------
        */

        $topUsedItems = Consumable::with('unitMeasure')
            ->get()
            ->map(function($item) {
                $keluar = ConsumableTransaction::where('consumable_id', $item->id)
                    ->where('type', 'OUT')
                    ->sum('quantity');
                $item->total_keluar = $keluar;
                return $item;
            })
            ->sortByDesc('total_keluar')
            ->take(5)
            ->values();

        $topUsedLabels = $topUsedItems->pluck('name')->toArray();
        $topUsedData = $topUsedItems->pluck('total_keluar')->toArray();

        /*
        |--------------------------------------------------------------------------
        | Top 5 Barang Paling Sering Masuk
        |--------------------------------------------------------------------------
        */

        $topInItems = Consumable::with('unitMeasure')
            ->get()
            ->map(function($item) {
                $masuk = ConsumableTransaction::where('consumable_id', $item->id)
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

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('history.index', compact(
            'transactions',
            'barangMasuk',
            'barangKeluar',
            'totalTransaksi',
            'totalBarang',
            'chartLabels',
            'barangMasukData',
            'barangKeluarData',
            'topUsedLabels',
            'topUsedData',
            'topInLabels',
            'topInData'
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

        $this->service->addStock(
            $request->consumable_id,
            $request->quantity,
            $request->note
        );

        return redirect()->back()
            ->with('success', 'Stock berhasil ditambahkan');
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

        try {

            $this->service->takeStock(
                $request->consumable_id,
                $request->quantity,
                $request->note
            );

            return redirect()->back()
                ->with('success', 'Barang berhasil digunakan');

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', $e->getMessage());

        }
    }

    /**
     * --------------------------------------------------------------------------
     * API Stock
     * --------------------------------------------------------------------------
     */
    public function getStock($id)
    {
        $stock = $this->service->getStock($id);

        return response()->json([

            'stock' => $stock

        ]);
    }
}