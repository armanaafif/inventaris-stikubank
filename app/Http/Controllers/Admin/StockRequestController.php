<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Services\ConsumableService;

class StockRequestController extends Controller
{
    protected $service;

    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    /**
     * 🔹 List semua request pending
     */
    public function index()
    {
        $requests = StockRequest::where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.requests', compact('requests'));
    }

    /**
     * 🔹 Approve request
     */
    public function approve($id)
    {
        $req = StockRequest::findOrFail($id);

        // 🚫 cegah double approve
        if ($req->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses');
        }

        // ⚠️ VALIDASI KRITIS (hindari stok minus)
        if ($req->type === 'OUT') {
            $currentStock = $this->service->getStock($req->consumable_id);

            if ($currentStock < $req->quantity) {
                return back()->with('error', 'Stok tidak mencukupi untuk approve');
            }
        }

        // ✅ Eksekusi ke transaksi asli
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

        // 🔄 update status
        $req->status = 'approved';
        $req->save();

        return back()->with('success', 'Request berhasil di-approve');
    }

    /**
     * 🔹 Reject request
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