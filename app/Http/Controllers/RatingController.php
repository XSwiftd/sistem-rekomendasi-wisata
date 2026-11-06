<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interaksi;
use App\Models\Wisata;

class RatingController extends Controller
{
    /**
     * Menyimpan atau memperbarui rating dan ulasan dari user.
     */
    public function store(Request $request, $wisataId)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        // Fallback user dummy (ID = 1) untuk keperluan testing lokal
        $userId = auth()->id() ?? 1;

        // Cari apakah user sudah pernah memberi rating pada wisata ini
        $interaksi = Interaksi::where('user_id', $userId)
            ->where('wisata_id', $wisataId)
            ->where('jenis_interaksi', 'rating')
            ->first();

        if ($interaksi) {
            // Update rating & ulasan yang sudah ada
            $interaksi->update([
                'rating'            => $request->rating,
                'ulasan'            => $request->ulasan,
                'tanggal_interaksi' => now(),
            ]);

            $message = 'Rating dan ulasan berhasil diperbarui!';
        } else {
            // Buat entri rating baru dengan ID manual (kompatibel ID CSV non-increment)
            $maxId = Interaksi::max('interaksi_id') ?? 0;

            Interaksi::create([
                'interaksi_id'      => $maxId + 1,
                'user_id'           => $userId,
                'wisata_id'         => $wisataId,
                'jenis_interaksi'   => 'rating',
                'rating'            => $request->rating,
                'ulasan'            => $request->ulasan,
                'tanggal_interaksi' => now(),
            ]);

            $message = 'Terima kasih, ulasan Anda berhasil dikirim!';
        }

        return redirect()->back()->with('success', $message);
    }
}