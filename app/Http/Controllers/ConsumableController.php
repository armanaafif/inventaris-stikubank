<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConsumableService;
use App\Models\Consumable;
use App\Models\StockRequest;
use App\Models\UnitMeasure;

class ConsumableController extends Controller
{
    protected $service;

    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar barang
     */
    public function index(Request $request)
    {
        $query = Consumable::with('unitMeasure');

        /*
        |--------------------------------------------------------------------------
        | Search barang
        |--------------------------------------------------------------------------
        */

        if ($request->search) {

            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );

        }

        $data = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Ambil total stok tiap barang
        |--------------------------------------------------------------------------
        */

        foreach ($data as $item) {

            $item->stock = $this->service->getStock($item->id);

        }

        return view('list', compact('data'));
    }

    /**
     * Menampilkan halaman tambah barang
     */
    public function create()
    {
        $units = UnitMeasure::latest()->get();

        return view('create', compact('units'));
    }

    /**
     * Menyimpan barang baru
     */
    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|string|max:255',

            'unit_measure_id' => 'required|exists:unit_measures,id',

            'minimum_stock' => 'required|integer|min:0',

            'initial_stock' => 'required|integer|min:0'

        ]);

        /*
        |--------------------------------------------------------------------------
        | Simpan barang
        |--------------------------------------------------------------------------
        */

        $item = Consumable::create([

            'name' => $request->name,

            'unit_measure_id' => $request->unit_measure_id,

            'minimum_stock' => $request->minimum_stock

        ]);

        /*
        |--------------------------------------------------------------------------
        | Tambahkan stok awal otomatis
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
     * Detail barang dan histori transaksi
     */
    public function show(Request $request, $id)
    {
        $item = Consumable::with('unitMeasure')
            ->findOrFail($id);

        $query = $item->transactions();

        /*
        |--------------------------------------------------------------------------
        | Filter transaksi
        |--------------------------------------------------------------------------
        */

        if ($request->type) {

            $query->where('type', $request->type);

        }

        $transactions = $query
            ->latest()
            ->get();

        $stock = $this->service->getStock($id);

        return view('detail', compact(
            'item',
            'stock',
            'transactions'
        ));
    }

    /**
     * Halaman monitoring stok
     */
    public function stock()
    {
        $data = Consumable::with('unitMeasure')
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Hitung stok setiap barang
        |--------------------------------------------------------------------------
        */

        foreach ($data as $item) {

            $item->stock = $this->service->getStock($item->id);

        }

        return view('stock', compact('data'));
    }

    /**
     * Tambah stok langsung
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
            ->with('success', 'Stok berhasil ditambahkan');
    }

    /**
     * Gunakan barang
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
     * API stok barang
     */
    public function getStock($id)
    {
        $stock = $this->service->getStock($id);

        return response()->json([

            'stock' => $stock

        ]);
    }
}