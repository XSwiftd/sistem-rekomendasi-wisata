<?php

namespace App\Services;

use App\Models\Wisata;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RecommenderService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.recommender.url', env('RECOMMENDER_API_URL', 'http://127.0.0.1:8001'));
    }

    /**
     * Mengambil rekomendasi personal untuk User ID tertentu.
     */
    public function getUserRecommendations(int $userId, int $limit = 6)
    {
        $cacheKey = "recommendations_user_ids_{$userId}_limit_{$limit}";

        // Simpan HANYA array wisata_id di Cache (aman dari issue unserialize)
        $wisataIds = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($userId, $limit) {
            try {
                $response = Http::timeout(3)->get("{$this->baseUrl}/recommend/user/{$userId}", [
                    'limit' => $limit
                ]);

                if ($response->successful()) {
                    $data = $response->json('data', []);
                    if (!empty($data)) {
                        return collect($data)->pluck('wisata_id')->toArray();
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Recommender Python API Error (getUserRecommendations): " . $e->getMessage());
            }

            return [];
        });

        // Jika dapat ID dari Python API, ambil data dari Database MySQL
        if (!empty($wisataIds)) {
            $wisatas = Wisata::withAvgRating()
                ->whereIn('wisata_id', $wisataIds)
                ->get()
                ->sortBy(fn($item) => array_search($item->wisata_id, $wisataIds))
                ->values();

            if ($wisatas->isNotEmpty()) {
                return $wisatas;
            }
        }

        // FALLBACK STRATEGY (Cold-Start / API Timeout)
        return $this->getPopularWisataFallback($limit);
    }

    /**
     * Mengambil rekomendasi Wisata Serupa (Item-to-Item).
     */
    public function getItemRecommendations(int $wisataId, int $limit = 4)
    {
        $cacheKey = "recommendations_item_ids_{$wisataId}_limit_{$limit}";

        $wisataIds = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($wisataId, $limit) {
            try {
                $response = Http::timeout(3)->get("{$this->baseUrl}/recommend/item/{$wisataId}", [
                    'limit' => $limit
                ]);

                if ($response->successful()) {
                    $data = $response->json('data', []);
                    if (!empty($data)) {
                        return collect($data)->pluck('wisata_id')->toArray();
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Recommender Python API Error (getItemRecommendations): " . $e->getMessage());
            }

            return [];
        });

        if (!empty($wisataIds)) {
            return Wisata::withAvgRating()
                ->whereIn('wisata_id', $wisataIds)
                ->get()
                ->sortBy(fn($item) => array_search($item->wisata_id, $wisataIds))
                ->values();
        }

        // FALLBACK: Ambil destinasi lain dalam kategori yang sama
        $current = Wisata::find($wisataId);
        if ($current) {
            return Wisata::withAvgRating()
                ->where('kategori', $current->kategori)
                ->where('wisata_id', '!=', $wisataId)
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }

        return collect();
    }

    /**
     * Trigger reload/retrain matriks TF-IDF di Python API saat ulasan baru dibuat.
     */
    public function triggerReloadModel(): void
    {
        try {
            Http::timeout(3)->post("{$this->baseUrl}/recommend/reload");
            Cache::flush();
        } catch (\Exception $e) {
            Log::warning("Recommender Python API Reload Error: " . $e->getMessage());
        }
    }

    /**
     * Fallback Cold-Start: Wisata populer berdasar rating & ulasan terbanyak.
     */
    protected function getPopularWisataFallback(int $limit = 6)
    {
        return Wisata::withAvgRating()
            ->orderByDesc('avg_rating')
            ->orderByDesc('total_ulasan')
            ->limit($limit)
            ->get();
    }
}