<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConsumableService;
use App\Models\Consumable;

class ConsumableController extends Controller
{
    protected $service;

    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    // 🔹 Tambah stok
    public function addStock(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $this->service->addStock(
            $request->consumable_id,
            $request->quantity,
            $request->note
        );

        return response()->json([
            'message' => 'Stok berhasil ditambahkan'
        ]);
    }

    // 🔹 Pakai barang
    public function takeStock(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->service->takeStock(
                $request->consumable_id,
                $request->quantity,
                $request->note
            );

            return response()->json([
                'message' => 'Barang berhasil digunakan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // 🔹 Ambil stok (API)
    public function getStock($id)
    {
        $stock = $this->service->getStock($id);

        return response()->json([
            'stock' => $stock
        ]);
    }

    // 🔹 Tampilkan list barang + stok + satuan
    public function index()
    {
        $data = Consumable::with('unitMeasure')->get();

        foreach ($data as $item) {
            $item->stock = $this->service->getStock($item->id);
        }

        return view('list', compact('data'));
    }

    public function show($id)
{
    $item = \App\Models\Consumable::with('unitMeasure', 'transactions')->findOrFail($id);

    $stock = $this->service->getStock($id);

    return view('detail', compact('item', 'stock'));
}
}