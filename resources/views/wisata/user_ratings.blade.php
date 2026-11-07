@extends('layouts.app')

@section('title', 'Riwayat Rating & Ulasan Saya — NusaTripRec')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom" style="border-color: var(--border) !important;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge rounded-pill" style="background: rgba(196, 101, 58, 0.1); color: var(--terracotta); font-size: 0.75rem; font-weight: 600;">
                    <i class="bi bi-clock-history me-1"></i> Aktivitas Pengguna
                </span>
            </div>
            <h2 class="mb-1" style="font-family: 'Instrument Serif', serif; font-size: 2.2rem; color: var(--navy); letter-spacing: -0.02em;">
                Riwayat Rating & Ulasan Saya
            </h2>
            <p class="text-muted small mb-0">
                Daftar destinasi wisata yang pernah Anda beri penilaian (digunakan sebagai basis preferensi rekomendasi persona Anda).
            </p>
        </div>
        <a href="{{ route('wisata.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="font-size: 0.85rem; font-weight: 500;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
        </a>
    </div>

    @if(isset($myRatings) && $myRatings->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($myRatings as $item)
                <div class="col">
                    <div class="card card-nusa h-100" style="background: var(--white);">
                        <div class="card-body p-4 d-flex flex-column">
                            <!-- Title & Rating Badge -->
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <h5 class="fw-bold mb-0 flex-grow-1" style="line-height: 1.3;">
                                    <a href="{{ route('wisata.show', $item->wisata_id) }}" class="text-decoration-none" style="color: var(--navy); font-size: 1.1rem;">
                                        {{ $item->wisata->nama_wisata ?? 'Wisata Tidak Ditemukan' }}
                                    </a>
                                </h5>
                                <span class="badge d-inline-flex align-items-center gap-1 px-2.5 py-1.5" style="background: #FEF08A; color: #854D0E; border-radius: var(--radius-full); font-size: 0.82rem; font-weight: 700; flex-shrink: 0;">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    {{ number_format($item->rating, 1) }}
                                </span>
                            </div>

                            <!-- Location Info -->
                            <p class="text-muted small mb-3">
                                <i class="bi bi-geo-alt me-1" style="color: var(--terracotta);"></i>
                                {{ $item->wisata->kabupaten_kota ?? '-' }}, {{ $item->wisata->provinsi ?? '-' }}
                            </p>

                            <!-- Review Box -->
                            @if(!empty($item->ulasan))
                                <div class="p-3 mb-3 flex-grow-1" style="background: var(--ivory); border-radius: var(--radius-sm); border-left: 3px solid var(--terracotta);">
                                    <p class="fst-italic text-secondary small mb-0" style="line-height: 1.5;">
                                        "{{ $item->ulasan }}"
                                    </p>
                                </div>
                            @else
                                <div class="mb-3 flex-grow-1">
                                    <span class="text-muted fst-italic small"><i class="bi bi-chat-left-text me-1 opacity-50"></i>Tanpa ulasan tertulis.</span>
                                </div>
                            @endif

                            <!-- Footer Date & Link -->
                            <div class="d-flex justify-content-between align-items-center text-muted small border-top pt-3 mt-auto" style="border-color: var(--border) !important; font-size: 0.8rem;">
                                <span>
                                    <i class="bi bi-calendar3 me-1 opacity-75"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal_interaksi)->format('d M Y, H:i') }}
                                </span>
                                <a href="{{ route('wisata.show', $item->wisata_id) }}" class="fw-semibold text-decoration-none d-inline-flex align-items-center" style="color: var(--terracotta); font-size: 0.82rem;">
                                    Detail Wisata <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
    {{ $myRatings->links('pagination::bootstrap-5') }}
</div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5 px-3 rounded-4" style="background: var(--white); border: 1.5px dashed var(--sand-muted);">
            <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; border-radius: 50%; background: var(--ivory-warm); color: var(--terracotta);">
                <i class="bi bi-star fs-2"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color: var(--navy);">Belum Ada Riwayat Rating</h5>
            <p class="text-muted small mb-4" style="max-width: 400px; margin-left: auto; margin-right: auto;">
                Anda belum pernah memberikan penilaian pada destinasi wisata mana pun. Berikan rating minimal 3.0 untuk mengaktifkan rekomendasi persona.
            </p>
            <a href="{{ route('wisata.index') }}" class="btn btn-terra btn-sm px-4">
                <i class="bi bi-compass me-1"></i> Mulai Jelajahi & Beri Rating
            </a>
        </div>
    @endif
</div>
@endsection