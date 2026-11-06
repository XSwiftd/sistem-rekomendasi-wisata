@extends('layouts.app')

@section('title', $wisata->nama_wisata . ' — NusaTripRec')

@push('styles')
<style>
    /* ---- Detail Hero ---- */
    .detail-hero {
        position: relative;
        border-radius: var(--radius-xl);
        overflow: hidden;
        height: 480px;
        margin-bottom: 32px;
    }
    .detail-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 6s ease;
    }
    .detail-hero:hover img { transform: scale(1.03); }
    .detail-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg,
            rgba(11,29,58,0.15) 0%,
            rgba(11,29,58,0.25) 40%,
            rgba(11,29,58,0.88) 100%);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 28px 36px;
    }
    .hero-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: var(--radius-full);
        background: rgba(11,29,58,0.5);
        backdrop-filter: blur(12px);
        color: var(--white);
        font-size: 0.82rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid rgba(255,255,255,0.12);
        transition: all var(--duration-fast) var(--ease-smooth);
    }
    .hero-back-btn:hover {
        background: rgba(11,29,58,0.7);
        color: var(--white);
    }
    .hero-rating-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: var(--radius-full);
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(12px);
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--navy);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    .hero-bottom-info h1 {
        font-family: 'Instrument Serif', serif;
        font-size: clamp(2rem, 4vw, 2.8rem);
        color: var(--white);
        line-height: 1.1;
        letter-spacing: -0.03em;
        margin-bottom: 8px;
    }
    .hero-location {
        color: rgba(255,255,255,0.75);
        font-size: 0.95rem;
    }
    .hero-cat-badge {
        display: inline-block;
        padding: 5px 16px;
        border-radius: var(--radius-full);
        background: var(--terracotta);
        color: var(--white);
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    /* ---- Highlight Stats ---- */
    .stat-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all var(--duration-fast) var(--ease-out);
    }
    .stat-card:hover {
        border-color: var(--sand-light);
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .stat-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 2px;
    }
    .stat-value {
        font-weight: 700;
        color: var(--navy);
        font-size: 0.95rem;
    }

    /* ---- Content Cards ---- */
    .content-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 32px;
        box-shadow: var(--shadow-xs);
    }
    .content-card-title {
        font-family: 'Instrument Serif', serif;
        font-size: 1.5rem;
        color: var(--navy);
        letter-spacing: -0.02em;
        margin-bottom: 16px;
    }

    /* Tags */
    .tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 16px;
        border-radius: var(--radius-full);
        background: var(--ivory);
        color: var(--slate-light);
        font-size: 0.82rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid var(--border);
        transition: all var(--duration-fast) var(--ease-out);
    }
    .tag-pill:hover {
        background: var(--navy);
        color: var(--white);
        border-color: var(--navy);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    /* ---- Reviews ---- */
    .review-card {
        padding: 20px;
        border-radius: var(--radius-md);
        background: var(--ivory);
        border: 1px solid var(--border);
        margin-bottom: 12px;
        transition: all var(--duration-fast) var(--ease-smooth);
    }
    .review-card:hover { background: var(--ivory-warm); }
    .review-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--navy-mid), var(--navy-light));
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    .review-stars {
        color: var(--sand);
        font-size: 0.8rem;
    }

    /* ---- Sidebar ---- */
    .sidebar-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 28px;
        box-shadow: var(--shadow-xs);
    }
    .sidebar-title {
        font-weight: 700;
        font-size: 1rem;
        color: var(--navy);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Rating Form */
    .rating-form .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 6px;
    }

    /* ---- Related Section ---- */
    .related-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 20px;
        text-decoration: none;
        transition: all var(--duration-fast) var(--ease-out);
        display: block;
        height: 100%;
    }
    .related-card:hover {
        border-color: var(--sand-light);
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
    }
    .related-card h4 {
        font-family: 'Instrument Serif', serif;
        font-size: 1.1rem;
        color: var(--navy);
        margin-bottom: 6px;
        line-height: 1.2;
    }

    /* ---- Bookmark Button ---- */
    .bookmark-btn {
        padding: 10px 24px;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.88rem;
        transition: all var(--duration-fast) var(--ease-out);
        border: none;
    }
    .bookmark-btn.saved {
        background: var(--terracotta);
        color: var(--white);
        box-shadow: 0 4px 14px rgba(196,101,58,0.3);
    }
    .bookmark-btn.not-saved {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(8px);
        color: var(--navy);
        box-shadow: 0 4px 14px rgba(0,0,0,0.1);
    }
    .bookmark-btn:hover { transform: translateY(-2px); }

    /* ---- Responsive ---- */
    @media (max-width: 991.98px) {
        .detail-hero { height: 380px; }
        .detail-hero-overlay { padding: 24px; }
    }
    @media (max-width: 767.98px) {
        .detail-hero { height: 320px; border-radius: var(--radius-lg); }
        .detail-hero-overlay { padding: 20px; }
        .hero-bottom-info h1 { font-size: 1.6rem; }
        .content-card { padding: 24px; }
        .sidebar-card { padding: 20px; }
    }
</style>
@endpush

@section('content')
@php
    $fallbackImages = [
        'Pantai' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1400&q=80',
        'Alam' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1400&q=80',
        'Budaya dan Sejarah' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1400&q=80',
        'Petualangan' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=1400&q=80',
        'Keluarga' => 'https://images.unsplash.com/photo-1513889961551-628c1e5e2ee9?auto=format&fit=crop&w=1400&q=80',
    ];
    $defaultImg = $fallbackImages[$wisata->kategori] ?? 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1400&q=80';
    $imgUrl = !empty($wisata->gambar) ? $wisata->gambar : $defaultImg;
@endphp

<div class="container">

    <!-- ====== IMMERSIVE HERO ====== -->
    <div class="detail-hero">
        <img src="{{ $imgUrl }}" alt="{{ $wisata->nama_wisata }}"
             onerror="this.onerror=null;this.src='{{ $defaultImg }}';">
        <div class="detail-hero-overlay">
            <!-- Top bar -->
            <div class="d-flex justify-content-between align-items-start">
                <a href="{{ route('wisata.index') }}" class="hero-back-btn">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div class="hero-rating-pill">
                    <i class="bi bi-star-fill text-warning"></i>
                    {{ $wisata->avg_rating ? number_format($wisata->avg_rating, 1) : '—' }}
                    <span class="fw-normal text-muted" style="font-size: 0.78rem;">({{ $wisata->total_ulasan ?? 0 }})</span>
                </div>
            </div>

            <!-- Bottom info -->
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div class="hero-bottom-info">
                    <span class="hero-cat-badge">{{ $wisata->kategori }}</span>
                    <h1>{{ $wisata->nama_wisata }}</h1>
                    <p class="hero-location mb-0">
                        <i class="bi bi-geo-alt-fill me-1" style="color: var(--sand);"></i>
                        {{ $wisata->kabupaten_kota }}, Provinsi {{ $wisata->provinsi }}
                    </p>
                </div>

                <!-- Bookmark Button -->
                <button type="button" id="btn-bookmark" data-id="{{ $wisata->wisata_id }}"
                        class="bookmark-btn {{ $isBookmarked ? 'saved' : 'not-saved' }}">
                    <i class="bi {{ $isBookmarked ? 'bi-bookmark-check-fill' : 'bi-bookmark-plus' }} me-1" id="icon-bookmark"></i>
                    <span id="text-bookmark">{{ $isBookmarked ? 'Tersimpan' : 'Simpan' }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ====== QUICK STATS ====== -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(196,101,58,0.08); color: var(--terracotta);">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <div class="stat-label">Estimasi Biaya</div>
                    <div class="stat-value">{{ $wisata->estimasi_biaya }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(201,169,110,0.12); color: var(--sand);">
                    <i class="bi bi-signpost-split"></i>
                </div>
                <div>
                    <div class="stat-label">Tingkat Medan</div>
                    <div class="stat-value">{{ $wisata->tingkat_kesulitan }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(11,29,58,0.06); color: var(--navy);">
                    <i class="bi bi-pin-map-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Koordinat</div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $wisata->latitude }},{{ $wisata->longitude }}"
                       target="_blank" class="stat-value text-decoration-none d-flex align-items-center gap-1" style="color: var(--terracotta);">
                        Buka Peta <i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- ====== MAIN CONTENT ====== -->
        <div class="col-lg-8">

            <!-- Description -->
            <div class="content-card mb-4">
                <h2 class="content-card-title">Tentang Destinasi</h2>
                <p class="text-muted mb-4" style="line-height: 1.8; font-size: 0.95rem;">
                    {{ $wisata->deskripsi }}
                </p>

                <h6 class="fw-bold text-uppercase small mb-3" style="letter-spacing: 1px; color: var(--muted); font-size: 0.7rem;">
                    Karakteristik & Tags <span class="fw-normal">(digunakan AI TF-IDF)</span>
                </h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(explode(',', $wisata->tags) as $tag)
                        <a href="{{ route('wisata.index', ['search' => trim($tag)]) }}" class="tag-pill">
                            <i class="bi bi-hash" style="font-size: 0.7rem; opacity: 0.5;"></i>{{ trim($tag) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Reviews List -->
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="content-card-title mb-1">Ulasan Wisatawan</h2>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">{{ $wisata->total_ulasan ?? 0 }} ulasan dari pengunjung</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background: var(--ivory-warm);">
                        <i class="bi bi-star-fill" style="color: var(--sand);"></i>
                        <span class="fw-bold" style="color: var(--navy);">{{ $wisata->avg_rating ? number_format($wisata->avg_rating, 1) : '0.0' }}</span>
                        <span class="text-muted" style="font-size: 0.78rem;">/ 5</span>
                    </div>
                </div>

                @forelse($ulasans as $ulasan)
                    <div class="review-card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="review-avatar">
                                    {{ strtoupper(mb_substr($ulasan->user->nama_pengguna ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 0.88rem; color: var(--navy);">
                                        {{ $ulasan->user->nama_pengguna ?? 'Pengunjung #' . $ulasan->user_id }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        {{ \Carbon\Carbon::parse($ulasan->tanggal_interaksi)->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= round($ulasan->rating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                                <span class="ms-1 fw-bold" style="color: var(--navy); font-size: 0.82rem;">
                                    {{ number_format($ulasan->rating, 1) }}
                                </span>
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 0.88rem; line-height: 1.65; padding-left: 52px;">
                            {{ $ulasan->ulasan ?? 'Memberikan rating tanpa komentar tertulis.' }}
                        </p>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-chat-square-text fs-1 d-block mb-3" style="color: var(--muted-light);"></i>
                        <p class="text-muted mb-0">Belum ada ulasan. Jadilah yang pertama memberikan penilaian!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ====== SIDEBAR ====== -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px; z-index: 5;">

                <!-- Rating Form -->
                <div class="sidebar-card mb-4" id="section-form-rating">
                    <div class="sidebar-title">
                        <i class="bi bi-pen" style="color: var(--terracotta);"></i>
                        Beri Penilaian
                    </div>
                    <p class="text-muted small mb-3" style="line-height: 1.6;">
                        Rating ≥ 4 bintang akan melatih mesin <strong>Content-Based Filtering</strong> untuk rekomendasi yang lebih akurat.
                    </p>

                    <form action="{{ route('wisata.rating.store', $wisata->wisata_id) }}" method="POST" class="rating-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Skor Kepuasan</label>
                            <select name="rating" class="form-select" required>
                                <option value="5" {{ ($userRating->rating ?? '') == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 — Luar Biasa)</option>
                                <option value="4" {{ ($userRating->rating ?? '') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 — Sangat Bagus)</option>
                                <option value="3" {{ ($userRating->rating ?? '') == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 — Cukup)</option>
                                <option value="2" {{ ($userRating->rating ?? '') == 2 ? 'selected' : '' }}>⭐⭐ (2 — Kurang)</option>
                                <option value="1" {{ ($userRating->rating ?? '') == 1 ? 'selected' : '' }}>⭐ (1 — Buruk)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ulasan Anda</label>
                            <textarea name="ulasan" rows="4" class="form-control"
                                      placeholder="Ceritakan pengalaman Anda di destinasi ini...">{{ $userRating->ulasan ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-terra w-100">
                            <i class="bi bi-send me-1"></i>
                            {{ $userRating ? 'Perbarui Penilaian' : 'Kirim Penilaian' }}
                        </button>
                    </form>
                </div>

                <!-- Navigation Card -->
                <div class="sidebar-card">
                    <div class="sidebar-title">
                        <i class="bi bi-compass" style="color: var(--sand);"></i>
                        Navigasi & Lokasi
                    </div>
                    <p class="small text-muted mb-3">
                        Koordinat: <code style="background: var(--ivory); padding: 2px 8px; border-radius: 6px; font-size: 0.8rem;">{{ $wisata->latitude }}, {{ $wisata->longitude }}</code>
                    </p>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $wisata->latitude }},{{ $wisata->longitude }}"
                       target="_blank" class="btn btn-navy w-100">
                        <i class="bi bi-map me-1"></i> Buka Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== RELATED DESTINATIONS ====== -->
    @if(isset($wisataSerupa) && $wisataSerupa->count() > 0)
        <div class="mt-5 pt-4">
            <div class="mb-4">
                <div class="section-eyebrow" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; color: var(--terracotta);">
                    Kategori {{ $wisata->kategori }}
                </div>
                <h2 style="font-family: 'Instrument Serif', serif; font-size: 1.8rem; color: var(--navy); letter-spacing: -0.02em;">
                    Destinasi Serupa
                </h2>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                @foreach($wisataSerupa as $serupa)
                    <div class="col">
                        <a href="{{ route('wisata.show', $serupa->wisata_id) }}" class="related-card">
                            <span class="d-inline-block px-2 py-1 rounded-pill mb-2" style="background: var(--ivory-warm); color: var(--muted); font-size: 0.72rem; font-weight: 600;">
                                {{ $serupa->provinsi }}
                            </span>
                            <h4>{{ $serupa->nama_wisata }}</h4>
                            <span class="text-muted" style="font-size: 0.85rem;">{{ $serupa->estimasi_biaya }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
document.getElementById('btn-bookmark')?.addEventListener('click', function () {
    const id = this.getAttribute('data-id');
    const btn = this;
    const textSpan = document.getElementById('text-bookmark');
    const icon = document.getElementById('icon-bookmark');

    fetch("{{ route('bookmark.toggle') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ wisata_id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'added') {
            btn.classList.remove('not-saved');
            btn.classList.add('saved');
            icon.className = 'bi bi-bookmark-check-fill me-1';
            textSpan.textContent = 'Tersimpan';
        } else if (data.status === 'removed') {
            btn.classList.remove('saved');
            btn.classList.add('not-saved');
            icon.className = 'bi bi-bookmark-plus me-1';
            textSpan.textContent = 'Simpan';
        }
    })
    .catch(err => console.error('Error:', err));
});
</script>
@endsection