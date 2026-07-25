<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ConsumableService;
use App\Models\Category;
use App\Models\Consumable;
use App\Models\ConsumableTransaction;
use App\Models\Location;
use App\Models\UnitMeasure;
use App\Models\StockRequest;
use App\Models\Borrowing;
use Illuminate\Validation\ValidationException;

class ConsumableController extends Controller
{
    protected $service;

    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    private function validateContinuousStockInput(Request $request): void
    {
        if ($request->inventory_type !== 'CONTINUOUS') {
            return;
        }

        if (!$request->filled('quantity')) {
            throw ValidationException::withMessages(['quantity' => 'Jumlah wajib diisi untuk barang CONTINUOUS.']);
        }
    }

    private function continuousBatchPayload(Request $request, $unitName = null): array
    {
        return [
            'brand' => $request->brand,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'specification' => $request->specification,
            'condition' => $request->condition,
            'quantity' => $request->quantity,
            'length_unit' => $unitName,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Daftar Barang
     * --------------------------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = Consumable::with(['unitMeasure', 'category', 'stocks.location']);

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
            $item->stock_summary = $this->service->stockSummary($item);
            $item->stock_display = $this->service->formatStock($item);
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
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('barang.create', compact('units', 'locations', 'categories'));
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
            'inventory_type' => 'required|in:UNIT,CONTINUOUS',
            'category_id' => 'nullable|exists:categories,id',
            'item_number' => 'nullable|digits_between:1,20',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'specification' => 'nullable|string',
            'description' => 'nullable|string',
            'unit_measure_id' => 'required|exists:unit_measures,id',
            'location_id' => 'nullable|exists:locations,id',
            'minimum_stock' => 'nullable|integer|min:0',
            'quantity' => 'nullable|numeric|min:0.01',
            'condition' => 'required|in:BARU,BEKAS,LAYAK,RUSAK',
            'status' => 'required|in:AKTIF,NONAKTIF',
            'purchase_receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
        $this->validateContinuousStockInput($request);

        $receiptPath = $request->hasFile('purchase_receipt')
            ? $request->file('purchase_receipt')->store('purchase-receipts', 'public')
            : null;

        $categoryId = $request->category_id ?: $this->service->defaultCategoryId();
        $locationId = $request->location_id ?: $this->service->defaultLocationId();
        $itemNumber = $this->service->normaliseItemNumber($request->item_number ?: $this->service->nextItemNumber($categoryId));
        $itemCode = $this->service->generateItemCode($categoryId, $itemNumber);

        if (Consumable::where('item_code', $itemCode)->exists()) {
            throw ValidationException::withMessages(['item_number' => 'Nomor barang sudah digunakan.']);
        }

        // Jika user adalah admin, langsung buat barang
        if (auth()->user()->role === 'admin') {
            $item = Consumable::create([
                'item_code' => $itemCode,
                'item_number' => $itemNumber,
                'category_id' => $categoryId,
                'name' => $request->name,
                'inventory_type' => $request->inventory_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'serial_number' => $request->serial_number,
                'specification' => $request->specification,
                'description' => $request->description,
                'unit_measure_id' => $request->unit_measure_id,
                'minimum_stock' => $request->minimum_stock,
                'condition' => $request->condition,
                'status' => $request->status,
                'purchase_receipt_path' => $receiptPath
            ]);

            if ($request->inventory_type === 'CONTINUOUS') {
                $item->load('unitMeasure');
                $this->service->addStock(
                    $item->id,
                    0,
                    'Stok awal barang',
                    $locationId,
                    $this->continuousBatchPayload($request, $item->unitMeasure?->name)
                );
            } elseif ($request->quantity > 0) {
                $this->service->addStock(
                    $item->id,
                    $request->quantity,
                    'Stok awal barang',
                    $locationId,
                    [
                        'brand' => $request->brand,
                        'model' => $request->model,
                        'serial_number' => $request->serial_number,
                        'specification' => $request->specification,
                        'condition' => $request->condition,
                    ]
                );
            }

            return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
        }

        // Jika bukan admin, buat request approval
        StockRequest::create([
            'request_type' => 'CREATE_ITEM',
            'user_id' => auth()->id(),
            'item_name' => $request->name,
            'inventory_type' => $request->inventory_type,
            'item_code' => $itemCode,
            'item_number' => $itemNumber,
            'category_id' => $categoryId,
            'brand' => $request->brand,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'specification' => $request->specification,
            'description' => $request->description,
            'unit_measure_id' => $request->unit_measure_id,
            'location_id' => $locationId,
            'minimum_stock' => $request->minimum_stock,
            'initial_stock' => $request->inventory_type === 'UNIT' ? $request->quantity : 0,
            'quantity' => $request->inventory_type === 'CONTINUOUS' ? $request->quantity : null,
            'length_amount' => $request->inventory_type === 'CONTINUOUS' ? $request->quantity : null,
            'length_unit' => $request->inventory_type === 'CONTINUOUS' ? UnitMeasure::find($request->unit_measure_id)?->name : null,
            'condition' => $request->condition,
            'item_status' => $request->status,
            'purchase_receipt_path' => $receiptPath,
            'status' => 'pending'
        ]);

        return redirect()
            ->route('barang.index')
            ->with('approval_pending', 'Barang berhasil diajukan dan sedang menunggu approval admin.');
    }

    /**
     * --------------------------------------------------------------------------
     * Detail Barang
     * --------------------------------------------------------------------------
     */
    public function edit($id)
    {
        $item = Consumable::findOrFail($id);
        $units = UnitMeasure::latest()->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('barang.edit', compact('item', 'units', 'locations', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = Consumable::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'inventory_type' => 'required|in:UNIT,CONTINUOUS',
            'category_id' => 'nullable|exists:categories,id',
            'item_number' => 'required|digits_between:1,20',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'specification' => 'nullable|string',
            'description' => 'nullable|string',
            'unit_measure_id' => 'required|exists:unit_measures,id',
            'minimum_stock' => 'nullable|integer|min:0',
            'condition' => 'required|in:BARU,BEKAS,LAYAK,RUSAK',
            'status' => 'required|in:AKTIF,NONAKTIF',
            'purchase_receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $categoryId = $request->category_id ?: $this->service->defaultCategoryId();
        $itemNumber = $this->service->normaliseItemNumber($request->item_number);
        $itemCode = $this->service->generateItemCode($categoryId, $itemNumber);

        if (Consumable::where('item_code', $itemCode)->whereKeyNot($item->id)->exists()) {
            throw ValidationException::withMessages(['item_number' => 'Nomor barang sudah digunakan.']);
        }

        $payload = [
            'name' => $request->name,
            'inventory_type' => $request->inventory_type,
            'category_id' => $categoryId,
            'item_number' => $itemNumber,
            'item_code' => $itemCode,
            'brand' => $request->brand,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'specification' => $request->specification,
            'description' => $request->description,
            'unit_measure_id' => $request->unit_measure_id,
            'minimum_stock' => $request->minimum_stock,
            'condition' => $request->condition,
            'status' => $request->status,
        ];

        if ($request->hasFile('purchase_receipt')) {
            if ($item->purchase_receipt_path) {
                Storage::disk('public')->delete($item->purchase_receipt_path);
            }
            $payload['purchase_receipt_path'] = $request->file('purchase_receipt')->store('purchase-receipts', 'public');
        }

        $item->update($payload);

        return redirect()->route('barang.show', $item->id)->with('success', 'Barang berhasil diperbarui');
    }

    public function nextItemNumber(Category $category)
    {
        return response()->json(['item_number' => $this->service->nextItemNumber($category->id)]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);

        $letters = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $request->name));
        $baseCode = substr($letters ?: 'KAT', 0, 3);
        $code = $baseCode;
        $suffix = 1;

        while (Category::where('code', $code)->exists()) {
            $suffix++;
            $code = substr($baseCode, 0, max(1, 10 - strlen((string) $suffix))) . $suffix;
        }

        $category = Category::create(['name' => $request->name, 'code' => $code]);

        return response()->json($category, 201);
    }

    public function show(Request $request, $id)
    {
        $item = Consumable::with(['unitMeasure', 'category', 'stocks.location'])->findOrFail($id);
        $query = $item->transactions();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->get();
        $stock = $this->service->getStock($id);
        $stockSummary = $this->service->stockSummary($item);

        $stockDistribution = $item->stocks()->with('location')->orderBy('id')->get();
        $borrowedStock = Borrowing::where('consumable_id', $id)
            ->where('status', 'BORROWED')
            ->sum('quantity');
        $totalStock = $item->inventory_type === 'UNIT' ? $stock + $borrowedStock : $stock;
        $locations = Location::where('is_active', true)->orderBy('name')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'item' => [
                    'id' => $item->id,
                    'item_code' => $item->item_code,
                    'name' => $item->name,
                    'inventory_type' => $item->inventory_type,
                    'category' => $item->category?->name,
                    'brand' => $item->brand,
                    'model' => $item->model,
                    'serial_number' => $item->serial_number,
                    'specification' => $item->specification,
                    'description' => $item->description,
                    'condition' => $item->condition,
                    'status' => $item->status,
                    'unit' => $item->unitMeasure?->name,
                    'minimum_stock' => $item->minimum_stock,
                    'purchase_receipt_url' => $item->purchase_receipt_path ? asset('storage/' . $item->purchase_receipt_path) : null,
                ],
                'stock' => $stock,
                'stock_summary' => $stockSummary,
                'stock_display' => $this->service->formatStock($item),
                'borrowed_stock' => $borrowedStock,
                'total_stock' => $totalStock,
                'locations' => $stockDistribution->map(fn ($row) => [
                    'stock_id' => $row->id,
                    'id' => $row->location_id,
                    'name' => $row->location?->name,
                    'batch_code' => $row->batch_code,
                    'quantity' => $item->inventory_type === 'CONTINUOUS' ? (float) $row->remaining_length : $row->quantity,
                    'roll_count' => $row->roll_count,
                    'initial_length' => $row->initial_length,
                    'remaining_length' => $row->remaining_length,
                    'length_unit' => $row->length_unit,
                ])->values(),
                'available_locations' => $locations->map(fn ($location) => [
                    'id' => $location->id,
                    'name' => $location->name,
                ])->values(),
            ]);
        }

        return view('barang.detail', compact(
            'item',
            'stock',
            'stockSummary',
            'borrowedStock',
            'totalStock',
            'transactions',
            'stockDistribution',
            'locations'
        ));
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
        $data = Consumable::with(['unitMeasure', 'stocks.location'])->latest()->get();

        foreach ($data as $item) {
            $item->stock = $this->service->getStock($item->id);
            $item->stock_summary = $this->service->stockSummary($item);
            $item->stock_display = $this->service->formatStock($item);
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
        $query = ConsumableTransaction::with(['consumable', 'consumable.unitMeasure', 'fromLocation', 'toLocation']);

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
        $barangTransfer = ConsumableTransaction::where('type', 'TRANSFER')->sum('quantity');
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
            'transactions', 'barangMasuk', 'barangKeluar', 'barangTransfer', 'totalTransaksi', 'totalBarang',
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
            'location_id' => 'nullable|exists:locations,id',
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'specification' => 'nullable|string',
            'condition' => 'nullable|in:BARU,BEKAS,LAYAK,RUSAK',
        ]);
        $item = Consumable::find($request->consumable_id);
        if ($item?->inventory_type === 'CONTINUOUS') {
            $request->merge(['inventory_type' => 'CONTINUOUS']);
            $this->validateContinuousStockInput($request);
        }

        // Jika user adalah admin, proses langsung
        if (auth()->user()->role === 'admin') {
            // Update item attributes if provided
            if ($item?->inventory_type === 'UNIT' && !$request->filled('quantity')) {
                return redirect()->back()->with('error', 'Jumlah wajib diisi untuk barang UNIT.');
            }
            if ($item) {
                $payload = array_filter([
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'serial_number' => $request->serial_number,
                    'specification' => $request->specification,
                    'condition' => $request->condition,
                ], function ($v) { return !is_null($v) && $v !== ''; });
                if (!empty($payload)) {
                    $item->update($payload);
                }
            }

            $item = Consumable::findOrFail($request->consumable_id);
            $item->load('unitMeasure');
            $this->service->addStock(
                $request->consumable_id,
                $item->inventory_type === 'CONTINUOUS' ? 0 : $request->quantity,
                $request->note,
                $request->location_id,
                $item->inventory_type === 'CONTINUOUS'
                    ? $this->continuousBatchPayload($request, $item->unitMeasure?->name)
                    : [
                        'brand' => $request->brand,
                        'model' => $request->model,
                        'serial_number' => $request->serial_number,
                        'specification' => $request->specification,
                        'condition' => $request->condition,
                    ]
            );

            return redirect()->back()->with('success', 'Stock berhasil ditambahkan');
        }

        // Jika bukan admin, buat request approval
        StockRequest::create([
            'consumable_id' => $request->consumable_id,
            'location_id' => $request->location_id ?: $this->service->defaultLocationId(),
            'quantity' => $request->quantity,
            'length_amount' => $item?->inventory_type === 'CONTINUOUS' ? $request->quantity : null,
            'length_unit' => $item?->inventory_type === 'CONTINUOUS' ? $item->unitMeasure?->name : null,
            'type' => 'IN',
            'note' => $request->note,
            'brand' => $request->brand,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'specification' => $request->specification,
            'condition' => $request->condition,
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
            'location_id' => 'nullable|exists:locations,id',
            'quantity' => 'required|numeric|min:0.01',
            'consumable_stock_id' => 'nullable|exists:consumable_stocks,id',
            'note' => 'nullable|string'
        ]);

        // Jika user adalah admin, proses langsung
        if (auth()->user()->role === 'admin') {
            try {
                $this->service->takeStock(
                    $request->consumable_id,
                    $request->quantity,
                    $request->note,
                    $request->location_id,
                    $request->consumable_stock_id
                );
                return redirect()->back()->with('success', 'Barang berhasil digunakan');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        // Jika bukan admin, buat request approval
        StockRequest::create([
            'consumable_id' => $request->consumable_id,
            'location_id' => $request->location_id,
            'quantity' => $request->quantity,
            'length_amount' => $request->quantity,
            'type' => 'OUT',
            'note' => $request->note,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Request penggunaan barang berhasil dikirim dan menunggu approval admin.');
    }

    public function transferStock(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|exists:consumables,id',
            'from_location_id' => 'required|exists:locations,id',
            'to_location_id' => 'required|exists:locations,id',
            'quantity' => 'required|numeric|min:0.01',
            'consumable_stock_id' => 'nullable|exists:consumable_stocks,id',
            'note' => 'nullable|string|max:500',
        ]);

        if ($request->from_location_id == $request->to_location_id) {
            return redirect()->back()->with('error', 'Lokasi asal dan tujuan tidak boleh sama.');
        }

        if (auth()->user()->role !== 'admin') {
            StockRequest::create([
                'request_type' => 'TRANSFER',
                'consumable_id' => $request->consumable_id,
                'from_location_id' => $request->from_location_id,
                'to_location_id' => $request->to_location_id,
                'quantity' => $request->quantity,
                'length_amount' => $request->quantity,
                'note' => $request->note,
                'status' => 'pending',
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('success', 'Request pindah lokasi berhasil dikirim dan menunggu approval admin.');
        }

        try {
            $this->service->transferStock(
                $request->consumable_id,
                $request->from_location_id,
                $request->to_location_id,
                $request->quantity,
                $request->note,
                auth()->id(),
                $request->consumable_stock_id
            );

            return redirect()->back()->with('success', 'Stok berhasil dipindahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
            'borrower_phone' => 'required|string|max:50',
            'borrower_unit' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'note' => 'nullable|string|max:500'
        ]);

        // Cek stok barang tersedia
        $item = Consumable::findOrFail($request->consumable_id);

        if ($item->inventory_type === 'CONTINUOUS') {
            return redirect()->back()->with('error', 'Barang continuous tidak dapat dipinjam.');
        }

        $currentStock = $this->service->getStock($request->consumable_id);
        
        if ($currentStock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . number_format($currentStock));
        }

        // Simpan data peminjaman ke tabel borrowings
        $borrowing = Borrowing::create([
            'consumable_id' => $request->consumable_id,
            'user_id' => auth()->id(), // ID user yang meminjam
            'borrower_name' => $request->borrower_name,
            'borrower_phone' => $request->borrower_phone,
            'borrower_unit' => $request->borrower_unit,
            'purpose' => $request->purpose,
            'quantity' => $request->quantity,
            'borrow_date' => $request->borrow_date,
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
