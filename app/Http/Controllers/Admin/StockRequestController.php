<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Models\Consumable;
use App\Services\ConsumableService;
use Illuminate\Http\Request;

/**
 * Class StockRequestController
 * 
 * Controller untuk mengelola permintaan stok (Stock Request).
 * Menangani proses approval, rejection, dan menampilkan daftar permintaan.
 */
class StockRequestController extends Controller
{
    /**
     * @var ConsumableService
     */
    protected $service;

    /**
     * Constructor - Menginjeksi service untuk manipulasi stok
     * 
     * @param ConsumableService $service
     */
    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    private function continuousBatchPayload($source, $unitName = null): array
    {
        return [
            'brand' => $source->brand,
            'model' => $source->model,
            'serial_number' => $source->serial_number,
            'specification' => $source->specification,
            'condition' => $source->condition,
            'quantity' => $source->quantity,
            'length_unit' => $source->length_unit ?: $unitName,
        ];
    }

    /**
     * Menampilkan daftar request dengan filter, search, dan statistik
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Query dasar dengan relasi yang diperlukan
        $query = StockRequest::with([
            'consumable.unitMeasure',
            'user',
            'location',
            'category',
            'fromLocation',
            'toLocation'
        ]);

        // Filter berdasarkan tipe request.
        if ($request->filled('type')) {
            if ($request->type === 'CREATE_ITEM' || $request->type === 'TRANSFER') {
                $query->where('request_type', $request->type);
            } else {
                $query->where('type', $request->type);
            }
        }

        // Filter berdasarkan status (pending / approved / rejected)
        if ($request->filled('status') && $request->status != '') {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan semua status, tidak hanya pending
            // Jika ingin default hanya pending, ubah baris ini menjadi:
            // $query->where('status', 'pending');
        }

        // Search berdasarkan nama barang lama atau nama barang baru yang diminta.
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('consumable', function ($consumableQuery) use ($request) {
                    $consumableQuery->where('name', 'like', '%' . $request->search . '%');
                })->orWhere('item_name', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil data dengan pagination (10 per halaman)
        $requests = $query->latest()->paginate(10)->withQueryString();

        // Statistik untuk tampilan
        $totalRequests = StockRequest::count();
        $totalPending = StockRequest::where('status', 'pending')->count();
        $totalApproved = StockRequest::where('status', 'approved')->count();
        $totalRejected = StockRequest::where('status', 'rejected')->count();

        return view('admin.requests', compact(
            'requests',
            'totalRequests',
            'totalPending',
            'totalApproved',
            'totalRejected'
        ));
    }

    /**
     * Approve request dan eksekusi perubahan stok
     * - Untuk tipe IN: menambah stok
     * - Untuk tipe OUT: mengurangi stok (dengan validasi stok mencukupi)
     * - Untuk request_type CREATE_ITEM: membuat barang baru
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        // Cari request atau gagal (404)
        $req = StockRequest::findOrFail($id);

        // Cegah request yang sudah diproses diproses ulang
        if ($req->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses');
        }

        // Handle CREATE_ITEM request type
        if ($req->request_type === 'CREATE_ITEM') {
            $categoryId = $req->category_id ?: $this->service->defaultCategoryId();
            $itemCode = $req->item_code ?: $this->service->generateItemCode($categoryId, $req->item_number);

            if (Consumable::where('item_code', $itemCode)->exists()) {
                return back()->with('error', 'Nomor barang sudah digunakan.');
            }

            $item = Consumable::create([
                'item_code' => $itemCode,
                'item_number' => $req->item_number ?: $this->service->nextItemNumber($categoryId),
                'category_id' => $categoryId,
                'name' => $req->item_name,
                'inventory_type' => $req->inventory_type ?: 'UNIT',
                'brand' => $req->brand,
                'model' => $req->model,
                'serial_number' => $req->serial_number,
                'specification' => $req->specification,
                'description' => $req->description,
                'unit_measure_id' => $req->unit_measure_id,
                'minimum_stock' => $req->minimum_stock,
                'condition' => $req->condition,
                'status' => $req->item_status,
                'purchase_receipt_path' => $req->purchase_receipt_path
            ]);

            if ($item->inventory_type === 'CONTINUOUS') {
                $item->load('unitMeasure');
                $this->service->addStock(
                    $item->id,
                    0,
                    'Stok awal barang',
                    $req->location_id ?: $this->service->defaultLocationId(),
                    $this->continuousBatchPayload($req, $item->unitMeasure?->name)
                );
            } elseif ($req->initial_stock > 0) {
                $this->service->addStock(
                    $item->id,
                    $req->initial_stock,
                    'Stok awal barang',
                    $req->location_id ?: $this->service->defaultLocationId(),
                    [
                        'brand' => $req->brand,
                        'model' => $req->model,
                        'serial_number' => $req->serial_number,
                        'specification' => $req->specification,
                        'condition' => $req->condition,
                    ]
                );
            }

            $req->status = 'approved';
            $req->save();

            return back()->with('success', 'Request tambah barang berhasil di-approve');
        }

        // Validasi stok untuk tipe OUT
        if ($req->type === 'OUT') {
            $currentStock = $this->service->getStock($req->consumable_id);
            if ($currentStock < $req->quantity) {
                return back()->with('error', 'Stok tidak mencukupi. Stok saat ini: ' . $currentStock);
            }
        }

        // Eksekusi perubahan stok berdasarkan tipe
        if ($req->request_type === 'TRANSFER') {
            try {
                $this->service->transferStock(
                    $req->consumable_id,
                    $req->from_location_id,
                    $req->to_location_id,
                    $req->quantity,
                    $req->note . ' [Approved by ' . auth()->user()->name . ']',
                    auth()->id()
                );
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        } elseif ($req->type === 'IN') {
                $item = Consumable::with('unitMeasure')->find($req->consumable_id);
                $this->service->addStock(
                    $req->consumable_id,
                    $req->quantity,
                    $req->note . ' [Approved by ' . auth()->user()->name . ']',
                    $req->location_id,
                    $item?->inventory_type === 'CONTINUOUS'
                        ? $this->continuousBatchPayload($req, $item->unitMeasure?->name)
                        : [
                            'brand' => $req->brand,
                            'model' => $req->model,
                            'serial_number' => $req->serial_number,
                            'specification' => $req->specification,
                            'condition' => $req->condition,
                        ]
            );
        } elseif ($req->type === 'OUT') {
            try {
                $this->service->takeStock(
                    $req->consumable_id,
                    $req->quantity,
                    $req->note . ' [Approved by ' . auth()->user()->name . ']',
                    $req->location_id
                );
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        // Update status request menjadi approved
        $req->status = 'approved';
        $req->save();

        return back()->with('success', 'Request berhasil di-approve');
    }

    /**
     * Reject request tanpa perubahan stok
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($id)
    {
        // Cari request atau gagal (404)
        $req = StockRequest::findOrFail($id);

        // Cegah request yang sudah diproses
        if ($req->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses');
        }

        // Update status menjadi rejected
        $req->status = 'rejected';
        $req->save();

        return back()->with('success', 'Request ditolak');
    }
}
