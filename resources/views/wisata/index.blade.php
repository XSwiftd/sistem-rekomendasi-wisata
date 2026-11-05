@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header & Filter Search -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-1">Eksplorasi Wisata Indonesia</h2>
            <p class="text-muted">Temukan destinasi terbaik sesuai minat liburanmu.</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('wisata.index') }}" method="GET" class="d-flex gap-2">
                <select name="kategori" class="form-select w-auto">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" class="form-control" placeholder="Cari nama / kota..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    <!-- Grid Card Wisata -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($wisatas as $item)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ $item->gambar ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80' }}" 
                         class="card-img-top" 
                         alt="{{ $item->nama_wisata }}" 
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary">{{ $item->kategori }}</span>
                            <span class="badge bg-secondary">{{ $item->tingkat_kesulitan }}</span>
                        </div>
                        <h5 class="card-title text-truncate">{{ $item->nama_wisata }}</h5>
                        <p class="card-text text-muted small mb-2">
                            <i class="bi bi-geo-alt"></i> {{ $item->kabupaten_kota }}, {{ $item->provinsi }}
                        </p>
                        <p class="card-text text-truncate small text-secondary">{{ $item->deskripsi }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center pb-3">
                        <span class="fw-bold text-success small">{{ $item->estimasi_biaya }}</span>
                        <a href="{{ route('wisata.show', $item->wisata_id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Tidak ada tempat wisata yang cocok dengan pencarianmu.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $wisatas->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection