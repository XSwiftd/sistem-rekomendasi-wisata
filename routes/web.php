<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WisataController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Rekomendasi Tempat Wisata Indonesia
|--------------------------------------------------------------------------
*/

// 1. Route Beranda / Katalog Wisata (L2.2)
Route::get('/', [WisataController::class, 'index'])->name('wisata.index');

// 2. Route Detail Wisata (L2.3)
Route::get('/wisata/{id}', [WisataController::class, 'show'])->name('wisata.show');

// 3. Route Placeholder Submit Rating (akan diimplementasikan penuh oleh Anggota 3 di L3.2)
Route::post('/wisata/{id}/rating', function ($id) {
    return redirect()->route('wisata.show', $id)->with('error', 'Fitur rating belum aktif. Akan diimplementasikan di Fase 3.');
})->name('wisata.rating');

// 4. Route Placeholder Toggle Bookmark (akan diimplementasikan penuh oleh Anggota 3 di L3.1)
Route::post('/wisata/{id}/bookmark', function ($id) {
    return response()->json(['message' => 'Fitur bookmark belum aktif. Akan diimplementasikan di Fase 3.'], 501);
})->name('wisata.bookmark');

// 5. Route Simulasi Switch User Aktif di Navbar (L2.1)
Route::get('/switch-user/{id}', function ($id) {
    session(['active_user_id' => (int) $id]);
    return redirect()->back()->with('success', "Berhasil beralih ke simulasi User ID #{$id}");
})->name('switch.user');