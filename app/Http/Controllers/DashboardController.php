<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\ConsumableTransaction;
use App\Models\StockRequest;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Total Barang
        |--------------------------------------------------------------------------
        */

        $totalBarang = Consumable::count();

        /*
        |--------------------------------------------------------------------------
        | Total Stok
        |--------------------------------------------------------------------------
        */

        $totalStock = ConsumableTransaction::selectRaw("
            COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END),0) -
            COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END),0)
            as total
        ")->value('total');

        /*
        |--------------------------------------------------------------------------
        | Pending Request
        |--------------------------------------------------------------------------
        */

        $pendingRequest = StockRequest::where(
            'status',
            'pending'
        )->count();

        return view('dashboard', compact(
            'totalBarang',
            'totalStock',
            'pendingRequest'
        ));
    }
}