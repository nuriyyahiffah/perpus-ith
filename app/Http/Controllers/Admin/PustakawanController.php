<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PustakawanController extends Controller
{
    public function index()
    {
        // Mengambil user dengan role 'pustakawan'
        $pustakawan = User::where('role', 'pustakawan')->latest()->get();
        return view('admin.pustakawan.index', compact('pustakawan'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Data pustakawan berhasil dihapus.');
    }
}
