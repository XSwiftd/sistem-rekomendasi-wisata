<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Interaksi;
use App\Services\RecommenderService;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    protected RecommenderService $recommenderService;

    public function __construct(RecommenderService $recommenderService)
    {
        $this->recommenderService = $recommenderService;
    }

    public function index(Request $request)
    {
        $activeUserId = session('active_user_id', 1);

        // Ambil Katalog Utama dengan Filter
        $query = Wisata::withAvgRating();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->provinsi);
        }

        $wisatas = $query->paginate(9)->withQueryString();

        // Data pendukung UI Filter
        $kategoris = Wisata::select('kategori')->distinct()->pluck('kategori');
        $provinsis = Wisata::select('provinsi')->distinct()->pluck('provinsi');

        // Safe Fallback jika tabel bookmarks belum di-migrate
        $bookmarkedIds = class_exists(\App\Models\Bookmark::class) && \Schema::hasTable('bookmarks')
            ? \App\Models\Bookmark::where('user_id', $activeUserId)->pluck('wisata_id')->toArray()
            : [];

        // Panggil Recommender Service dari Python API Engine
        $rekomendasiWisatas = $this->recommenderService->getUserRecommendations($activeUserId, 6);

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

        $wisata = Wisata::withAvgRating()->findOrFail($id);
        
        $ulasans = Interaksi::with('user')
            ->where('wisata_id', $id)
            ->latest('tanggal_interaksi')
            ->get();

        $userRating = Interaksi::where('user_id', $activeUserId)
            ->where('wisata_id', $id)
            ->first();

        // Safe Fallback jika tabel bookmarks belum di-migrate
        $isBookmarked = class_exists(\App\Models\Bookmark::class) && \Schema::hasTable('bookmarks')
            ? \App\Models\Bookmark::where('user_id', $activeUserId)->where('wisata_id', $id)->exists()
            : false;

        // Panggil Wisata Serupa dari Python API Engine (Item-to-Item)
        $wisataSerupa = $this->recommenderService->getItemRecommendations($id, 4);

        return view('wisata.show', compact(
            'wisata',
            'ulasans',
            'userRating',
            'isBookmarked',
            'wisataSerupa'
        ));
    }

    public function storeRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        $activeUserId = session('active_user_id', 1);

        Interaksi::updateOrCreate(
            [
                'user_id'   => $activeUserId,
                'wisata_id' => $id,
            ],
            [
                'rating'            => $request->rating,
                'ulasan'            => $request->ulasan,
                'tanggal_interaksi' => now(),
            ]
        );

        // Trigger Retrain Model Python FastAPI
        $this->recommenderService->triggerReloadModel();

        return redirect()->back()->with('success', 'Penilaian Anda berhasil disimpan & mesin rekomendasi telah diperbarui!');
    }

    public function userRatings()
{
    $activeUserId = session('active_user_id', 1);

    // Ambil semua ulasan & rating yang pernah diberikan oleh user aktif
    $myRatings = Interaksi::with('wisata')
        ->where('user_id', $activeUserId)
        ->latest('tanggal_interaksi')
        ->paginate(10);

    return view('wisata.user_ratings', compact('myRatings'));
}
}