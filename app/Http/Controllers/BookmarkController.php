<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Wisata;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        $activeUserId = session('active_user_id', 1);

        // Ambil HANYA bookmark milik user yang sedang aktif
        $bookmarks = Bookmark::with(['wisata' => function ($q) {
            $q->withAvgRating();
        }])
        ->where('user_id', $activeUserId)
        ->latest('bookmark_id')
        ->paginate(9);

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function toggle($wisataId)
    {
        $activeUserId = session('active_user_id', 1);
    
        $bookmark = Bookmark::where('user_id', $activeUserId)
            ->where('wisata_id', $wisataId)
            ->first();
    
        if ($bookmark) {
            $bookmark->delete();
            $status = 'removed';
            $message = 'Wisata dihapus dari koleksi tersimpan.';
        } else {
            Bookmark::create([
                'user_id'   => $activeUserId,
                'wisata_id' => $wisataId,
            ]);
            $status = 'added';
            $message = 'Wisata berhasil disimpan ke koleksi!';
        }
    
        // Jika dipanggil via JavaScript (AJAX / Fetch)
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status'  => $status,
                'message' => $message,
            ]);
        }
    
        return redirect()->back()->with('success', $message);
    }
}