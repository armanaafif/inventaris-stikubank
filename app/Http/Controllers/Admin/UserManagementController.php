<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * Daftar User dengan Filter
     * --------------------------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan status
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan role
        if ($request->role && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        // Search berdasarkan nama atau email
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Statistik
        $totalUser = User::count();
        $pendingUser = User::where('status', 'pending')->count();
        $approvedUser = User::where('status', 'approved')->count();
        $adminCount = User::where('role', 'admin')->count();
        $staffCount = User::where('role', 'staff')->count();

        return view('admin.users', compact(
            'users',
            'totalUser',
            'pendingUser',
            'approvedUser',
            'adminCount',
            'staffCount'
        ));
    }

    /**
     * --------------------------------------------------------------------------
     * Approve User (Setujui Pendaftaran)
     * --------------------------------------------------------------------------
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);

        if ($user->status === 'approved') {
            return redirect()->back()->with('error', 'User sudah diapprove sebelumnya');
        }

        $user->update([
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', '✅ User ' . $user->name . ' berhasil diapprove');
    }

    /**
     * --------------------------------------------------------------------------
     * Reject User (Tolak Pendaftaran)
     * --------------------------------------------------------------------------
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);

        if ($user->status === 'approved') {
            return redirect()->back()->with('error', 'User sudah diapprove, tidak bisa ditolak');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', '❌ Pendaftaran user ' . $userName . ' berhasil ditolak');
    }

    /**
     * --------------------------------------------------------------------------
     * Update Role User
     * --------------------------------------------------------------------------
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,staff'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', 'Role user berhasil diupdate menjadi ' . $request->role);
    }

    /**
     * --------------------------------------------------------------------------
     * Tambah Staff Baru (Langsung dari Admin)
     * --------------------------------------------------------------------------
     */
    public function createStaff(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,staff'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai dengan password.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        // Jika validasi gagal, kembali ke form dengan error
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('showCreateModal', true);
        }

        // Buat user baru
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'approved'
            ]);

            $roleText = $user->role == 'admin' ? 'Admin' : 'Staff';

            return redirect()->route('admin.users')
                ->with('success', '✅ ' . $roleText . ' baru berhasil ditambahkan! Email: ' . $user->email);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage())
                ->with('showCreateModal', true);
        }
    }
}