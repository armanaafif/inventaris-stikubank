<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Services\ConsumableService;
use Illuminate\Http\Request;

class StockRequestController extends Controller
{
    protected $service;

    /**
     * Inject service untuk manipulasi stok
     */
    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar request dengan filter, search, dan statistik
     */
    public function index(Request $request)
    {
        $query = StockRequest::with(['consumable.unitMeasure', 'user']);

        // Filter berdasarkan tipe (IN / OUT)
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Search berdasarkan nama barang
        if ($request->search) {
            $query->whereHas('consumable', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil hanya status pending
        $requests = $query
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Statistik
        $totalPending  = StockRequest::where('status', 'pending')->count();
        $totalApproved = StockRequest::where('status', 'approved')->count();
        $totalRejected = StockRequest::where('status', 'rejected')->count();

        return view('admin.requests', compact(
            'requests',
            'totalPending',
            'totalApproved',
            'totalRejected'
        ));
    }

    /**
     * Approve request dan eksekusi perubahan stok
     */
    public function approve($id)
    {
        $req = StockRequest::findOrFail($id);

        // Cegah request diproses ulang
        if ($req->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses');
        }

        // Validasi stok jika OUT
        if ($req->type === 'OUT') {
            $currentStock = $this->service->getStock($req->consumable_id);

            if ($currentStock < $req->quantity) {
                return back()->with('error', 'Stok tidak mencukupi');
            }
        }

        // Eksekusi perubahan stok
        if ($req->type === 'IN') {
            $this->service->addStock(
                $req->consumable_id,
                $req->quantity,
                $req->note
            );
        }

        if ($req->type === 'OUT') {
            $this->service->takeStock(
                $req->consumable_id,
                $req->quantity,
                $req->note
            );
        }

        // Update status
        $req->status = 'approved';
        $req->save();

        return back()->with('success', 'Request berhasil di-approve');
    }

    /**
     * Reject request tanpa perubahan stok
     */
    public function reject($id)
    {
        $req = StockRequest::findOrFail($id);

        if ($req->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses');
        }

        $req->status = 'rejected';
        $req->save();

        return back()->with('success', 'Request ditolak');
    }
}