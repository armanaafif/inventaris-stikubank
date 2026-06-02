<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\ConsumableService;
use Illuminate\Http\Request; // <-- TAMBAHKAN IMPORT INI

class BorrowingController extends Controller
{
    protected $service;

    public function __construct(ConsumableService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar peminjaman dengan filter dan statistik
     */
    public function index(Request $request) // <-- TAMBAHKAN Request $request
    {
        // Query dasar dengan relasi
        $query = Borrowing::with([
            'consumable.unitMeasure',
            'user'
        ]);

        // FILTER: Search berdasarkan nama peminjam atau nama barang
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('borrower_name', 'like', '%' . $search . '%')
                  ->orWhereHas('consumable', function($sub) use ($search) {
                      $sub->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // FILTER: Berdasarkan status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Urutkan dari terbaru dan paginate
        $borrowings = $query->latest()->paginate(15)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK PEMINJAMAN
        |--------------------------------------------------------------------------
        */

        // Total semua peminjaman
        $totalBorrowings = Borrowing::count();

        // Pending (menunggu approval)
        $pendingBorrowings = Borrowing::where('status', 'PENDING')->count();

        // Aktif/BORROWED (sedang dipinjam)
        $activeBorrowings = Borrowing::where('status', 'BORROWED')->count();

        // Sudah dikembalikan
        $returnedBorrowings = Borrowing::where('status', 'RETURNED')->count();

        // Terlambat (status BORROWED dan tanggal kembali sudah lewat)
        $lateBorrowings = Borrowing::where('status', 'BORROWED')
            ->whereDate('return_date', '<', now())
            ->count();

        return view('admin.borrowings', compact(
            'borrowings',
            'totalBorrowings',
            'pendingBorrowings',
            'activeBorrowings',
            'returnedBorrowings',
            'lateBorrowings'
        ));
    }

    public function approve($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'PENDING') {
            return back()->with(
                'error',
                'Peminjaman sudah diproses'
            );
        }

        $stock = $this->service->getStock(
            $borrowing->consumable_id
        );

        if ($stock < $borrowing->quantity) {
            return back()->with(
                'error',
                'Stok tidak mencukupi'
            );
        }

        $this->service->takeStock(
            $borrowing->consumable_id,
            $borrowing->quantity,
            'Peminjaman disetujui oleh admin'
        );

        $borrowing->update([
            'status' => 'BORROWED',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return back()->with(
            'success',
            'Peminjaman berhasil disetujui'
        );
    }

    public function reject(Request $request, $id) // <-- TAMBAHKAN Request $request
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'PENDING') {
            return back()->with(
                'error',
                'Peminjaman sudah diproses'
            );
        }

        $borrowing->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => auth()->id(),
            'rejected_at' => now()
        ]);

        return back()->with(
            'success',
            'Peminjaman ditolak'
        );
    }

    public function returnItem($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'BORROWED') {
            return back()->with(
                'error',
                'Barang belum dipinjam'
            );
        }

        $this->service->addStock(
            $borrowing->consumable_id,
            $borrowing->quantity,
            'Pengembalian barang oleh: ' . $borrowing->borrower_name
        );

        $borrowing->update([
            'status' => 'RETURNED',
            'returned_at' => now()
        ]);

        return back()->with(
            'success',
            'Barang berhasil dikembalikan'
        );
    }
}