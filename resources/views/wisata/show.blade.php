@extends('layouts.app')

@section('content')
<div class="container py-4">
    <a href="{{ route('wisata.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Katalog
    </a>

    <div class="row g-4">
        <!-- Kolom Info Wisata -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                <img src="{{ $wisata->gambar ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80' }}" 
                     class="img-fluid w-100" 
                     alt="{{ $wisata->nama_wisata }}" 
                     style="max-height: 420px; object-fit: cover;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-primary mb-2">{{ $wisata->kategori }}</span>
                            <h2 class="fw-bold">{{ $wisata->nama_wisata }}</h2>
                            <p class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $wisata->kabupaten_kota }}, {{ $wisata->provinsi }}</p>
                        </div>
                        <button class="btn btn-outline-danger" id="btnBookmark" data-id="{{ $wisata->wisata_id }}">
                            <i class="bi bi-bookmark-heart"></i> Bookmark
                        </button>
                    </div>

                    <hr>
                    <h5 class="fw-bold">Deskripsi</h5>
                    <p class="text-secondary leading-relaxed">{{ $wisata->deskripsi }}</p>

                    <h5 class="fw-bold mt-4">Tags & Karakteristik</h5>
                    <div>
                        @foreach(explode(',', $wisata->tags) as $tag)
                            <span class="badge bg-light text-dark border me-1">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Form & Daftar Review -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Beri Ulasan & Rating</h5>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('wisata.rating.store', $wisata->wisata_id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rating Bintang:</label>
                            <select name="rating" class="form-select w-auto" required>
                                <option value="" disabled selected>Pilih Rating</option>
                                <option value="5">⭐⭐⭐⭐⭐ (5 - Luar Biasa)</option>
                                <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                                <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                <option value="2">⭐⭐ (2 - Kurang)</option>
                                <option value="1">⭐ (1 - Buruk)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ulasan:</label>
                            <textarea name="ulasan" rows="3" class="form-control" placeholder="Bagikan kesan Anda tentang wisata ini..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                    </form>
                </div>
            </div>

            <!-- Daftar Komentar -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Ulasan Pengunjung</h5>
                    @forelse($wisata->interaksis as $review)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $review->user->nama_pengguna ?? 'Pengunjung' }}</strong>
                                <span class="badge bg-warning text-dark">{{ $review->rating }} ⭐</span>
                            </div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($review->tanggal_interaksi)->translatedFormat('d M Y') }}</small>
                            <p class="text-secondary mt-1 mb-0">{{ $review->ulasan ?? 'Tidak ada ulasan teks.' }}</p>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada ulasan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Info Ringkas & Widget -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 p-3 mb-4">
                <h5 class="fw-bold mb-3">Informasi Wisata</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Estimasi Biaya:</span>
                        <span class="fw-bold text-success">{{ $wisata->estimasi_biaya }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Tingkat Kesulitan:</span>
                        <span class="badge bg-secondary">{{ $wisata->tingkat_kesulitan }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Koordinat:</span>
                        <small>{{ $wisata->latitude }}, {{ $wisata->longitude }}</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btnBookmark')?.addEventListener('click', function () {
    const id = this.getAttribute('data-id');
    fetch("{{ route('bookmark.toggle') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ wisata_id: id })
    })
    .then(res => res.json())
    .then(data => alert(data.message))
    .catch(err => console.error(err));
});
</script>
@endsection