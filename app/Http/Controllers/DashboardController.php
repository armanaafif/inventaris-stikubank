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
        | Total Stock
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

        /*
        |--------------------------------------------------------------------------
        | Barang Menipis
        |--------------------------------------------------------------------------
        */

        $barangMenipis = 0;

        $items = Consumable::all();

        foreach ($items as $item) {

            $stock = ConsumableTransaction::where(
                'consumable_id',
                $item->id
            )->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END),0) -
                COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END),0)
                as total
            ")->value('total');

            if ($stock <= $item->minimum_stock) {

                $barangMenipis++;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Recent Activity
        |--------------------------------------------------------------------------
        */

        $recentTransactions = ConsumableTransaction::with([
            'consumable',
            'consumable.unitMeasure'
        ])
        ->latest()
        ->take(5)
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Grafik Barang Masuk
        |--------------------------------------------------------------------------
        */

        $barangMasuk = ConsumableTransaction::where(
            'type',
            'IN'
        )->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | Grafik Barang Keluar
        |--------------------------------------------------------------------------
        */

        $barangKeluar = ConsumableTransaction::where(
            'type',
            'OUT'
        )->sum('quantity');

        return view('dashboard', compact(
            'totalBarang',
            'totalStock',
            'pendingRequest',
            'barangMenipis',
            'recentTransactions',
            'barangMasuk',
            'barangKeluar'
        ));
    }
}