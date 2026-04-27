<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Menampilkan semua notifikasi user
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Menggunakan scope forUser (pastikan ada di Model) atau manual where
        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->paginate(15);

        // Menghitung notifikasi yang kolom 'sudah_dibaca' bernilai false (0)
        $unreadCount = Notification::where('user_id', $userId)
            ->where('sudah_dibaca', false)
            ->count();

        return view('notifikasi.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Pastikan user hanya bisa akses notifikasi mereka sendiri
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        // Update kolom 'sudah_dibaca'
        $notification->update(['sudah_dibaca' => true]);

        // Menggunakan 'url_aksi' sesuai migrasi baru
        if ($notification->url_aksi) {
            return redirect($notification->url_aksi);
        }

        return redirect()->route('notifikasi.index');
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return redirect()->route('notifikasi.index')
            ->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * Menghapus notifikasi tunggal
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi telah dihapus.');
    }

    /**
     * Menghapus semua notifikasi milik user
     */
    public function destroyAll()
    {
        Notification::where('user_id', Auth::id())->delete();

        return redirect()->route('notifikasi.index')
            ->with('success', 'Semua notifikasi telah dihapus.');
    }

    /**
     * Get unread notifications count (untuk AJAX/Realtime)
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('sudah_dibaca', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}