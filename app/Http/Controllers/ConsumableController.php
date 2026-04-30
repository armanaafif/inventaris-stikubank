<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConsumableService;
use App\Models\Consumable;
use App\Models\StockRequest;

class ConsumableController extends Controller
{
    protected $service;

    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    /**
     * Tambah stok langsung (tanpa approval)
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
     * Gunakan barang (stok keluar)
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
     * API untuk mengambil stok
     */
    public function getStock($id)
    {
        $stock = $this->service->getStock($id);

        return response()->json([
            'stock' => $stock
        ]);
    }

    /**
     * Menampilkan daftar barang
     */
    public function index(Request $request)
    {
        $query = Consumable::with('unitMeasure');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $data = $query->paginate(10)->withQueryString();

        foreach ($data as $item) {
            $item->stock = $this->service->getStock($item->id);
        }

        return view('list', compact('data'));
    }

    /**
     * Detail barang dan histori transaksi
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

        return view('detail', compact('item', 'stock', 'transactions'));
    }
}