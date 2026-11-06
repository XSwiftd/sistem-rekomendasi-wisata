<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interaksi;
use App\Models\Wisata;

class BookmarkController extends Controller
{
    /**
     * Menampilkan halaman daftar bookmark milik user.
     */
    public function index()
    {
        // Menggunakan user_id = 1 sebagai dummy active user untuk simulasi testing
        $userId = auth()->id() ?? 1;

        $bookmarks = Interaksi::with('wisata')
            ->where('user_id', $userId)
            ->where('jenis_interaksi', 'bookmark')
            ->orderBy('tanggal_interaksi', 'desc')
            ->get();

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle Bookmark via AJAX (Tambah jika belum ada, Hapus jika sudah ada).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'wisata_id' => 'required|exists:wisatas,wisata_id',
        ]);

        $userId = auth()->id() ?? 1;
        $wisataId = $request->wisata_id;

        // Cek apakah user sudah mem-bookmark wisata ini
        $existingBookmark = Interaksi::where('user_id', $userId)
            ->where('wisata_id', $wisataId)
            ->where('jenis_interaksi', 'bookmark')
            ->first();

        if ($existingBookmark) {
            // Jika sudah ada -> Hapus (Unbookmark)
            $existingBookmark->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Wisata berhasil dihapus dari bookmark.',
                'is_bookmarked' => false
            ]);
        } else {
            // Generate interaksi_id baru (max ID + 1) untuk penanganan ID non-autoincrement
            $maxId = Interaksi::max('interaksi_id') ?? 0;

            Interaksi::create([
                'interaksi_id'      => $maxId + 1,
                'user_id'           => $userId,
                'wisata_id'         => $wisataId,
                'jenis_interaksi'   => 'bookmark',
                'rating'            => null,
                'ulasan'            => null,
                'tanggal_interaksi' => now(),
            ]);

            return response()->json([
                'status' => 'added',
                'message' => 'Wisata berhasil disimpan ke bookmark!',
                'is_bookmarked' => true
            ]);
        }
    }
}