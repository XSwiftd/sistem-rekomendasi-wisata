<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\RatingController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Rekomendasi Tempat Wisata Indonesia
|--------------------------------------------------------------------------
*/

// 1. Beranda & Katalog Wisata
Route::get('/', [WisataController::class, 'index'])->name('wisata.index');

// 2. Detail Wisata
Route::get('/wisata/{id}', [WisataController::class, 'show'])->name('wisata.show');

// 3. Fitur Bookmark (AJAX Toggle & Daftar Bookmark)

Route::get('/bookmark', [BookmarkController::class, 'index'])->name('bookmark.index');
Route::post('/bookmark/{wisataId}', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');
// 4. Fitur Submit Rating & Ulasan (Menggunakan RatingController dari branch temanmu)
Route::post('/wisata/{id}/rating', [RatingController::class, 'store'])->name('wisata.rating.store');

// 5. Route Simulasi Switch User Aktif (Navbar Debugger)
Route::get('/switch-user/{id}', function ($id) {
    session(['active_user_id' => (int) $id]);
    return redirect()->back()->with('success', "Berhasil beralih ke simulasi User ID #{$id}");
})->name('switch.user');

// Route untuk melihat riwayat ulasan & rating user aktif
Route::get('/riwayat-rating', [WisataController::class, 'userRatings'])->name('wisata.user_ratings');