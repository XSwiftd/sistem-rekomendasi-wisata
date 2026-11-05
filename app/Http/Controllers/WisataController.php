<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        $query = Wisata::query();

        // Fitur Pencarian Nama
        if ($request->filled('search')) {
            $query->where('nama_wisata', 'like', '%' . $request->search . '%')
                  ->orWhere('kabupaten_kota', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Data Kategori Unik untuk dropdown filter
        $kategoriList = Wisata::select('kategori')->distinct()->pluck('kategori');

        // Paginate data wisata
        $wisatas = $query->paginate(9)->withQueryString();

        return view('wisata.index', compact('wisatas', 'kategoriList'));
    }

    public function show($id)
    {
        $wisata = Wisata::with(['interaksis' => function ($query) {
            $query->where('jenis_interaksi', 'rating')->with('user')->latest('tanggal_interaksi');
        }])->findOrFail($id);

        return view('wisata.show', compact('wisata'));
    }
}