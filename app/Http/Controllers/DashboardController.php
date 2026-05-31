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

        $pendingRequest = StockRequest::where('status', 'pending')->count();

        /*
        |--------------------------------------------------------------------------
        | Barang Menipis
        |--------------------------------------------------------------------------
        */

        $barangMenipis = 0;
        $items = Consumable::all();

        foreach ($items as $item) {
            $stock = ConsumableTransaction::where('consumable_id', $item->id)
                ->selectRaw("
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
        | Total Barang Masuk & Keluar (Overall)
        |--------------------------------------------------------------------------
        */

        $barangMasuk = ConsumableTransaction::where('type', 'IN')->sum('quantity');
        $barangKeluar = ConsumableTransaction::where('type', 'OUT')->sum('quantity');
        $totalTransaksi = ConsumableTransaction::count();

        /*
        |--------------------------------------------------------------------------
        | GRAFIK 1: Data untuk 6 Bulan Terakhir
        |--------------------------------------------------------------------------
        */

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

            $barangMasukData[] = (int) ConsumableTransaction::where('type', 'IN')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            $barangKeluarData[] = (int) ConsumableTransaction::where('type', 'OUT')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');
        }

        /*
        |--------------------------------------------------------------------------
        | GRAFIK 2: Top 5 Barang Paling Sering Dipakai
        |--------------------------------------------------------------------------
        */

        $topUsedItems = Consumable::with('unitMeasure')
            ->get()
            ->map(function($item) {
                $keluar = (int) ConsumableTransaction::where('consumable_id', $item->id)
                    ->where('type', 'OUT')
                    ->sum('quantity');
                $item->total_keluar = $keluar;
                return $item;
            })
            ->sortByDesc('total_keluar')
            ->take(5)
            ->values();

        $topUsedLabels = $topUsedItems->pluck('name')->toArray();
        $topUsedData = $topUsedItems->pluck('total_keluar')->toArray();

        /*
        |--------------------------------------------------------------------------
        | GRAFIK 3: Top 5 Barang Paling Sering Masuk
        |--------------------------------------------------------------------------
        */

        $topInItems = Consumable::with('unitMeasure')
            ->get()
            ->map(function($item) {
                $masuk = (int) ConsumableTransaction::where('consumable_id', $item->id)
                    ->where('type', 'IN')
                    ->sum('quantity');
                $item->total_masuk = $masuk;
                return $item;
            })
            ->sortByDesc('total_masuk')
            ->take(5)
            ->values();

        $topInLabels = $topInItems->pluck('name')->toArray();
        $topInData = $topInItems->pluck('total_masuk')->toArray();

        /*
        |--------------------------------------------------------------------------
        | Return View dengan Semua Data
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'totalBarang',
            'totalStock',
            'pendingRequest',
            'barangMenipis',
            'recentTransactions',
            'barangMasuk',
            'barangKeluar',
            'totalTransaksi',
            'chartLabels',
            'barangMasukData',
            'barangKeluarData',
            'topUsedLabels',
            'topUsedData',
            'topInLabels',
            'topInData'
        ));
    }
}