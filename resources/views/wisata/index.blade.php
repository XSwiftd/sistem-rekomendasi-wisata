@extends('layouts.app')

@section('title', 'Jelajahi Destinasi Wisata Indonesia — NusaTripRec')

@push('styles')
<style>
    /* ==========================================================================
       1. HERO SECTION & FLOATING SEARCH DOCK FIX
       ========================================================================== */
    .hero-editorial {
        position: relative;
        border-radius: var(--radius-xl);
        overflow: hidden;
        min-height: 400px;
        display: flex;
        align-items: flex-end;
        background: var(--navy);
        margin-bottom: 32px; /* Diberikan margin positif agar TIDAK menumpuk search dock */
    }

    .hero-editorial::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
        opacity: 0.35;
        transition: transform 8s ease;
    }

    .hero-editorial:hover::before { 
        transform: scale(1.03); 
    }

    .hero-editorial::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(170deg,
            rgba(11,29,58,0.3) 0%,
            rgba(11,29,58,0.6) 50%,
            rgba(11,29,58,0.92) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 48px 48px 56px;
        max-width: 680px;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: var(--radius-full);
        background: rgba(201,169,110,0.15);
        border: 1px solid rgba(201,169,110,0.25);
        color: var(--sand);
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .hero-title {
        font-family: 'Instrument Serif', serif;
        font-size: clamp(2.2rem, 5vw, 3.4rem);
        color: var(--white);
        line-height: 1.08;
        letter-spacing: -0.03em;
        margin-bottom: 16px;
    }

    .hero-title em {
        color: var(--sand);
        font-style: italic;
    }

    .hero-subtitle {
        color: rgba(255,255,255,0.7);
        font-size: 0.98rem;
        line-height: 1.6;
        max-width: 520px;
    }

    /* ---- Search Dock ---- */
    .search-dock {
        position: relative;
        z-index: 5;
        max-width: 960px;
        margin: 0 auto 40px auto;
        padding: 20px 24px;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(20px);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(11,29,58,0.06);
    }

    .search-dock .form-control,
    .search-dock .form-select {
        background: var(--ivory);
        border: 1.5px solid rgba(11,29,58,0.08);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        font-size: 0.9rem;
    }

    .search-dock .form-control:focus,
    .search-dock .form-select:focus {
        background: var(--white);
        border-color: var(--terracotta);
        box-shadow: 0 0 0 4px rgba(196,101,58,0.08);
    }

    .search-icon-wrap {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    /* ==========================================================================
       2. CATEGORY PILLS & HEADERS
       ========================================================================== */
    .cat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        border-radius: var(--radius-full);
        font-size: 0.84rem;
        font-weight: 600;
        text-decoration: none;
        background: var(--white);
        color: var(--slate-light);
        border: 1.5px solid var(--border);
        transition: all var(--duration-fast) var(--ease-out);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .cat-pill:hover {
        color: var(--terracotta);
        border-color: var(--terracotta);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .cat-pill.active {
        background: var(--navy);
        color: var(--white);
        border-color: var(--navy);
        box-shadow: 0 4px 14px rgba(11,29,58,0.2);
    }

    .section-eyebrow {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: var(--terracotta);
        margin-bottom: 4px;
    }

    .section-title {
        font-family: 'Instrument Serif', serif;
        font-size: 1.8rem;
        color: var(--navy);
        letter-spacing: -0.02em;
    }

    /* ==========================================================================
       3. BENTO GRID & CARD LAYOUT FIX
       ========================================================================== */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    /* Target item pertama agar tampil sebagai Bento Highlight Card */
    .bento-grid .wisata-card:first-child {
        grid-column: span 2;
    }
    .bento-grid .wisata-card:first-child .card-visual { 
        height: 320px; 
    }
    .bento-grid .wisata-card:first-child .card-body-content h3 { 
        font-size: 1.5rem; 
    }

    /* Base Card Structure */
    .wisata-card {
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: transform var(--duration) var(--ease-out), box-shadow var(--duration) var(--ease-out);
    }

    .wisata-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
    }

    /* Card Image Container & Absolute Badges */
    .card-visual {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background-color: var(--navy-mid);
        flex-shrink: 0;
    }

    .card-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s var(--ease-out);
    }

    .wisata-card:hover .card-visual img {
        transform: scale(1.05);
    }

    .card-badge-category {
        position: absolute;
        top: 14px;
        left: 14px;
        z-index: 3;
        padding: 5px 14px;
        border-radius: var(--radius-full);
        background: rgba(11,29,58,0.75);
        backdrop-filter: blur(8px);
        color: var(--white);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        border: 1px solid rgba(255,255,255,0.15);
    }

    .card-badge-rating {
        position: absolute;
        top: 14px;
        right: 14px;
        z-index: 3;
        padding: 5px 12px;
        border-radius: var(--radius-full);
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px);
        color: var(--navy);
        font-size: 0.78rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .card-location-overlay {
        position: absolute;
        bottom: 12px;
        left: 14px;
        right: 14px;
        z-index: 3;
        color: var(--white);
        font-size: 0.78rem;
        font-weight: 500;
        text-shadow: 0 2px 6px rgba(0,0,0,0.6);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Card Content & Flex Fill */
    .card-body-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }

    .card-body-content h3 {
        font-family: 'Instrument Serif', serif;
        font-size: 1.25rem;
        color: var(--navy);
        margin-bottom: 8px;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .card-tag {
        display: inline-block;
        padding: 3px 10px;
        border-radius: var(--radius-full);
        background: var(--ivory);
        color: var(--slate-light);
        font-size: 0.72rem;
        font-weight: 500;
        border: 1px solid var(--border);
    }

    .card-footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 14px;
        border-top: 1px solid var(--border);
        margin-top: auto; /* Memaksa footer menempel rapat di paling bawah card */
    }

    .price-label {
        font-size: 0.62rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
        font-weight: 600;
    }

    .price-value {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--navy);
    }

    /* ==========================================================================
       4. EMPTY STATE & RESPONSIVE BREAKPOINTS
       ========================================================================== */
    .empty-state {
        background: var(--white);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        padding: 64px 32px;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--ivory-warm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    @media (max-width: 991.98px) {
        .bento-grid { 
            grid-template-columns: repeat(2, 1fr); 
        }
        .bento-grid .wisata-card:first-child { 
            grid-column: span 2; 
        }
        .hero-content { 
            padding: 36px 32px 48px; 
        }
    }

    @media (max-width: 767.98px) {
        .bento-grid { 
            grid-template-columns: 1fr; 
        }
        .bento-grid .wisata-card:first-child { 
            grid-column: span 1; 
        }
        .bento-grid .wisata-card:first-child .card-visual { 
            height: 220px; 
        }
        .hero-editorial { 
            min-height: 320px; 
        }
        .hero-content { 
            padding: 24px; 
        }
        .hero-title { 
            font-size: 2rem; 
        }
        .search-dock { 
            padding: 16px; 
        }
    }
</style>
@endpush

@section('content')
<div class="container py-3">

    <!-- ====== CINEMATIC HERO ====== -->
    <div class="hero-editorial">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <i class="bi bi-stars"></i> Content-Based AI Discovery
            </div>
            <h1 class="hero-title">
                Temukan Pesona<br><em>Nusantara</em>
            </h1>
            <p class="hero-subtitle mb-0">
                Rekomendasi wisata yang dipersonalisasi dari profil perjalanan, bookmark, dan ulasan terbaik Anda.
            </p>
        </div>
    </div>

    <!-- ====== SEARCH DOCK ====== -->
    <div class="search-dock">
        <form action="{{ route('wisata.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-lg-5 col-md-12">
                <div class="position-relative">
                    <div class="search-icon-wrap">
                        <i class="bi bi-search"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           style="padding-left: 42px;"
                           placeholder="Cari destinasi, kota, atau kata kunci..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <select name="provinsi" class="form-select">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsis as $prov)
                        <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>
                            {{ $prov }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-terra flex-grow-1" style="padding: 11px 20px;">
                    <i class="bi bi-sliders me-1"></i> Cari
                </button>
                @if(request()->anyFilled(['search', 'kategori', 'provinsi', 'kesulitan']))
                    <a href="{{ route('wisata.index') }}" 
                       class="btn btn-light border d-flex align-items-center justify-content-center" 
                       style="padding: 11px 14px; border-radius: var(--radius-sm);" 
                       title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- ====== CATEGORY PILLS ====== -->
    <div class="d-flex align-items-center gap-2 overflow-auto pb-2 mb-4" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">
        <a href="{{ route('wisata.index', array_merge(request()->except('kategori', 'page'))) }}"
           class="cat-pill {{ !request('kategori') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap"></i> Semua
        </a>
        @foreach($kategoris as $kat)
            <a href="{{ route('wisata.index', array_merge(request()->except('page'), ['kategori' => $kat])) }}"
               class="cat-pill {{ request('kategori') == $kat ? 'active' : '' }}">
                {{ $kat }}
            </a>
        @endforeach
    </div>

    <!-- ====== AI RECOMMENDATIONS SECTION ====== -->
   <!-- ====== AI RECOMMENDATIONS SECTION (FASTAPI PYTHON ENGINE) ====== -->
   @if(isset($rekomendasiWisatas) && $rekomendasiWisatas->count() > 0)
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="section-eyebrow">
                        <i class="bi bi-stars me-1"></i> Content-Based AI Engine
                    </div>
                    <h2 class="section-title mb-0">Rekomendasi Khusus Persona Anda</h2>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($rekomendasiWisatas as $rec)
                    @php
                        $fallbackImages = [
                            'Pantai' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                            'Alam' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=800&q=80',
                            'Budaya dan Sejarah' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=800&q=80',
                            'Petualangan' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=800&q=80',
                            'Keluarga' => 'https://images.unsplash.com/photo-1513889961551-628c1e5e2ee9?auto=format&fit=crop&w=800&q=80',
                        ];
                        $defaultImg = $fallbackImages[$rec->kategori] ?? 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80';
                        $imgUrl = !empty($rec->gambar) ? $rec->gambar : $defaultImg;
                    @endphp

                    <div class="col">
                        <div class="wisata-card" style="border: 1.5px solid rgba(196,101,58,0.25); background: var(--white);">
                            <div class="card-visual" style="height: 180px;">
                                <img src="{{ $imgUrl }}" alt="{{ $rec->nama_wisata }}" loading="lazy"
                                     onerror="this.onerror=null;this.src='{{ $defaultImg }}';">
                                <span class="card-badge-category" style="background: var(--terracotta);">
                                    <i class="bi bi-stars me-1"></i> Match AI
                                </span>
                                <span class="card-badge-rating">
                                    <i class="bi bi-star-fill text-warning me-1"></i>{{ $rec->avg_rating ? number_format($rec->avg_rating, 1) : 'Baru' }}
                                </span>
                            </div>
                            <div class="card-body-content">
                                <h3 style="font-size: 1.15rem;">{{ $rec->nama_wisata }}</h3>
                                <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.82rem;">
                                    {{ \Illuminate\Support\Str::limit($rec->deskripsi, 80) }}
                                </p>
                                <div class="card-footer-content">
                                    <span class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $rec->kabupaten_kota }}</span>
                                    <a href="{{ route('wisata.show', $rec->wisata_id) }}" class="btn btn-terra" style="padding: 6px 16px; font-size: 0.8rem;">
                                        Lihat <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ====== CATALOG HEADER ====== -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <div class="section-eyebrow">Direktori Nusantara</div>
            <h2 class="section-title mb-0">{{ $wisatas->total() }} Destinasi</h2>
        </div>
    </div>

    <!-- ====== BENTO CARD GRID ====== -->
    @if($wisatas->count() > 0)
        <div class="bento-grid mb-5">
            @foreach($wisatas as $item)
                @php
                    $fallbackImages = [
                        'Pantai' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                        'Alam' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=800&q=80',
                        'Budaya dan Sejarah' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=800&q=80',
                        'Petualangan' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=800&q=80',
                        'Keluarga' => 'https://images.unsplash.com/photo-1513889961551-628c1e5e2ee9?auto=format&fit=crop&w=800&q=80',
                    ];
                    $defaultImg = $fallbackImages[$item->kategori] ?? 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80';
                    $imgUrl = !empty($item->gambar) ? $item->gambar : $defaultImg;
                    $isSaved = in_array($item->wisata_id, $bookmarkedIds ?? []);
                @endphp

                <div class="wisata-card">
                    <!-- Image & Badges Overlay -->
                    <div class="card-visual">
                        <img src="{{ $imgUrl }}" 
                             alt="{{ $item->nama_wisata }}" 
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ $defaultImg }}';">

                        <span class="card-badge-category">{{ $item->kategori }}</span>

                        <span class="card-badge-rating">
                            <i class="bi bi-star-fill text-warning me-1"></i>{{ $item->avg_rating ? number_format($item->avg_rating, 1) : 'Baru' }}
                        </span>

                        <div class="card-location-overlay">
                            <i class="bi bi-geo-alt-fill me-1" style="color: var(--sand);"></i>{{ $item->kabupaten_kota }}, {{ $item->provinsi }}
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body-content">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <h3>{{ $item->nama_wisata }}</h3>
                            @if($isSaved)
                                <i class="bi bi-bookmark-fill flex-shrink-0" style="color: var(--terracotta); font-size: 1.1rem; margin-top: 2px;"></i>
                            @endif
                        </div>

                        <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.6; font-size: 0.85rem;">
                            {{ \Illuminate\Support\Str::limit($item->deskripsi, 100) }}
                        </p>

                        <!-- Tags -->
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach(array_slice(explode(',', $item->tags), 0, 3) as $tag)
                                <span class="card-tag">#{{ trim($tag) }}</span>
                            @endforeach
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer-content">
                            <div>
                                <div class="price-label">Estimasi Biaya</div>
                                <div class="price-value">{{ $item->estimasi_biaya }}</div>
                            </div>
                            <a href="{{ route('wisata.show', $item->wisata_id) }}" class="btn btn-terra" style="padding: 8px 20px; font-size: 0.84rem;">
                                Jelajahi <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $wisatas->links('pagination::bootstrap-5') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-compass fs-2" style="color: var(--muted);"></i>
            </div>
            <h3 class="font-editorial mb-2" style="font-size: 1.5rem;">Destinasi Tidak Ditemukan</h3>
            <p class="text-muted mx-auto mb-4" style="max-width: 400px;">
                Tidak ada destinasi yang cocok dengan pencarian Anda. Coba kata kunci atau filter yang berbeda.
            </p>
            <a href="{{ route('wisata.index') }}" class="btn btn-terra">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Pencarian
            </a>
        </div>
    @endif

</div>
@endsection