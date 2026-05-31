<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\ConsumableTransaction;
use App\Models\StockRequest;
use Illuminate\Support\Facades\DB;

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
        | Grafik Barang Masuk & Keluar (Last 6 Months)
        |--------------------------------------------------------------------------
        */

        $months = collect(range(5, 0))->map(function($i) {
            return now()->subMonths($i)->format('Y-m');
        });

        $barangMasukChart = [];
        $barangKeluarChart = [];

        foreach ($months as $month) {
            $startDate = date('Y-m-01 00:00:00', strtotime($month));
            $endDate = date('Y-m-t 23:59:59', strtotime($month));

            $masuk = ConsumableTransaction::where('type', 'IN')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            $keluar = ConsumableTransaction::where('type', 'OUT')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            $barangMasukChart[] = (int) $masuk;
            $barangKeluarChart[] = (int) $keluar;
        }

        $chartMonths = $months->map(function($month) {
            return date('M Y', strtotime($month));
        })->toArray();

        /*
        |--------------------------------------------------------------------------
        | Grafik 5 Barang dengan Stok Terendah
        |--------------------------------------------------------------------------
        */

        $lowStockItems = Consumable::with('unitMeasure')
            ->get()
            ->map(function($item) {
                $stock = ConsumableTransaction::where('consumable_id', $item->id)
                    ->selectRaw("
                        COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END),0) -
                        COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END),0)
                        as total
                    ")->value('total') ?? 0;

                $item->current_stock = $stock;
                return $item;
            })
            ->sortBy('current_stock')
            ->take(5)
            ->values();

        $lowStockNames = $lowStockItems->pluck('name')->toArray();
        $lowStockValues = $lowStockItems->pluck('current_stock')->toArray();

        /*
        |--------------------------------------------------------------------------
        | Grafik Distribusi Kondisi Barang
        |--------------------------------------------------------------------------
        */

        $conditionData = Consumable::select('condition', DB::raw('count(*) as total'))
            ->groupBy('condition')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->condition => $item->total];
            });

        $conditionLabels = ['BARU', 'BEKAS', 'LAYAK', 'RUSAK'];
        $conditionValues = [];

        foreach ($conditionLabels as $label) {
            $conditionValues[] = $conditionData[$label] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Grafik Distribusi Status Barang
        |--------------------------------------------------------------------------
        */

        $statusData = Consumable::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->status => $item->total];
            });

        $statusLabels = ['AKTIF', 'NONAKTIF'];
        $statusValues = [];

        foreach ($statusLabels as $label) {
            $statusValues[] = $statusData[$label] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Total Barang Masuk & Keluar (Overall)
        |--------------------------------------------------------------------------
        */

        $barangMasuk = ConsumableTransaction::where('type', 'IN')->sum('quantity');
        $barangKeluar = ConsumableTransaction::where('type', 'OUT')->sum('quantity');

        return view('dashboard', compact(
            'totalBarang',
            'totalStock',
            'pendingRequest',
            'barangMenipis',
            'recentTransactions',
            'barangMasuk',
            'barangKeluar',
            // Data untuk grafik
            'chartMonths',
            'barangMasukChart',
            'barangKeluarChart',
            'lowStockNames',
            'lowStockValues',
            'conditionLabels',
            'conditionValues',
            'statusLabels',
            'statusValues'
        ));
    }
}