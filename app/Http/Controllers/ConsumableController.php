<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConsumableService;
use App\Models\Consumable;
use App\Models\StockRequest;

class ConsumableController extends Controller
{
    /**
     * Service untuk handle logika stok
     */
    protected $service;

    /**
     * Inject service
     */
    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    /**
     * ===============================
     * STAFF: AJUKAN TAMBAH STOK (IN)
     * ===============================
     */
    public function addStock(Request $request)
    {
        // 🔒 Hanya staff yang boleh request
        if (auth()->user()->role !== 'staff') {
            abort(403, 'Hanya staff yang boleh mengajukan request');
        }

        // ✅ Validasi input
        $request->validate([
            'consumable_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        // 📌 Simpan sebagai request (PENDING)
        StockRequest::create([
            'consumable_id' => $request->consumable_id,
            'quantity' => $request->quantity,
            'type' => 'IN',
            'note' => $request->note,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);

        return redirect()->back()
            ->with('success', 'Permintaan tambah stok dikirim, menunggu approval admin');
    }

    /**
     * ===============================
     * STAFF: AJUKAN PENGGUNAAN (OUT)
     * ===============================
     */
    public function takeStock(Request $request)
    {
        // 🔒 Hanya staff
        if (auth()->user()->role !== 'staff') {
            abort(403, 'Hanya staff yang boleh mengajukan request');
        }

        $request->validate([
            'consumable_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        StockRequest::create([
            'consumable_id' => $request->consumable_id,
            'quantity' => $request->quantity,
            'type' => 'OUT',
            'note' => $request->note,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);

        return redirect()->back()
            ->with('success', 'Permintaan penggunaan dikirim, menunggu approval admin');
    }

    /**
     * ===============================
     * API: AMBIL TOTAL STOK
     * ===============================
     */
    public function getStock($id)
    {
        $stock = $this->service->getStock($id);

        return response()->json([
            'stock' => $stock
        ]);
    }

    /**
     * ===============================
     * LIST BARANG + SEARCH + PAGINATION
     * ===============================
     */
    public function index(Request $request)
    {
        $query = Consumable::with('unitMeasure');

        // 🔍 Search berdasarkan nama
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 📄 Pagination + keep query string
        $data = $query->paginate(10)->withQueryString();

        // 🔢 Inject stock (derived data)
        foreach ($data as $item) {
            $item->stock = $this->service->getStock($item->id);
        }

        return view('list', compact('data'));
    }

    /**
     * ===============================
     * DETAIL BARANG + HISTORY TRANSAKSI
     * ===============================
     */
    public function show(Request $request, $id)
    {
        $item = Consumable::with('unitMeasure')->findOrFail($id);

        // 🔎 Filter transaksi
        $query = $item->transactions();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->get();

        // 🔢 Hitung stok
        $stock = $this->service->getStock($id);

        return view('detail', compact('item', 'stock', 'transactions'));
    }
}