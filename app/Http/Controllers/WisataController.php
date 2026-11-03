<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Interaksi;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        $activeUserId = session('active_user_id', 1);

        // Query dasar dengan rata-rata rating dan jumlah ulasan
        $query = Wisata::withAvg(['interaksis as avg_rating' => function ($q) {
            $q->where('jenis_interaksi', 'rating')->whereNotNull('rating');
        }], 'rating')
        ->withCount(['interaksis as total_ulasan' => function ($q) {
            $q->where('jenis_interaksi', 'rating');
        }]);

        // 1. Filter Pencarian Kata Kunci (Nama Wisata / Tags / Deskripsi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_wisata', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%")
                  ->orWhere('kabupaten_kota', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter Provinsi
        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->provinsi);
        }

        // 4. Filter Tingkat Kesulitan
        if ($request->filled('kesulitan')) {
            $query->where('tingkat_kesulitan', $request->kesulitan);
        }

        // Ambil data wisata dengan pagination (12 item per halaman)
        $wisatas = $query->orderBy('wisata_id', 'asc')->paginate(12)->withQueryString();

        // Ambil daftar unik Kategori & Provinsi untuk dropdown filter
        $kategoris = Wisata::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $provinsis = Wisata::select('provinsi')->distinct()->orderBy('provinsi')->pluck('provinsi');

        // Ambil daftar wisata_id yang sudah di-bookmark oleh user aktif (untuk status tombol bookmark)
        $bookmarkedIds = Interaksi::where('user_id', $activeUserId)
            ->where('jenis_interaksi', 'bookmark')
            ->pluck('wisata_id')
            ->toArray();

        // Placeholder variabel rekomendasi (akan diisi penuh pada Fase 5)
        $rekomendasiWisatas = collect([]);

        return view('wisata.index', compact(
            'wisatas',
            'kategoris',
            'provinsis',
            'bookmarkedIds',
            'rekomendasiWisatas'
        ));
    }
    public function show($id)
    {
        $activeUserId = session('active_user_id', 1);

        // Ambil detail wisata beserta rata-rata rating & total ulasan
        $wisata = Wisata::withAvg(['interaksis as avg_rating' => function ($q) {
                $q->where('jenis_interaksi', 'rating')->whereNotNull('rating');
            }], 'rating')
            ->withCount(['interaksis as total_ulasan' => function ($q) {
                $q->where('jenis_interaksi', 'rating');
            }])
            ->findOrFail($id);

        // Ambil daftar ulasan & rating dari user lain untuk wisata ini
        $ulasans = Interaksi::with('user')
            ->where('wisata_id', $id)
            ->where('jenis_interaksi', 'rating')
            ->orderBy('tanggal_interaksi', 'desc')
            ->get();

        // Cek apakah user yang sedang aktif sudah mem-bookmark wisata ini
        $isBookmarked = Interaksi::where('user_id', $activeUserId)
            ->where('wisata_id', $id)
            ->where('jenis_interaksi', 'bookmark')
            ->exists();

        // Cek apakah user yang sedang aktif sudah pernah memberi rating pada wisata ini
        $userRating = Interaksi::where('user_id', $activeUserId)
            ->where('wisata_id', $id)
            ->where('jenis_interaksi', 'rating')
            ->first();

        // Wisata serupa berdasarkan kategori yang sama (sebagai alternatif sebelum AI Recommender Fase 5 aktif)
        $wisataSerupa = Wisata::where('kategori', $wisata->kategori)
            ->where('wisata_id', '!=', $wisata->wisata_id)
            ->limit(4)
            ->get();

        return view('wisata.show', compact(
            'wisata',
            'ulasans',
            'isBookmarked',
            'userRating',
            'wisataSerupa'
        ));
    }
}