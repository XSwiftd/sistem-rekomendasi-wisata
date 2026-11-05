<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\RatingController;

Route::get('/', [WisataController::class, 'index'])->name('wisata.index');
Route::get('/wisata/{wisata}', [WisataController::class, 'show'])->name('wisata.show');

Route::get('/bookmark', [BookmarkController::class, 'index'])->name('bookmark.index');
Route::post('/bookmark/toggle', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');

Route::post('/wisata/{wisata}/rating', [RatingController::class, 'store'])->name('wisata.rating.store');