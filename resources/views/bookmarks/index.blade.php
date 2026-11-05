@extends('layouts.app') 

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold">Bookmark Saya</h2>

    @if($bookmarks->isEmpty())
        <div class="alert alert-info text-center py-4">
            <p class="mb-0">Belum ada tempat wisata yang disimpan ke bookmark.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($bookmarks as $item)
                @if($item->wisata)
                <div class="col" id="bookmark-card-{{ $item->wisata->wisata_id }}">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ $item->wisata->gambar ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80' }}" 
                             class="card-img-top" 
                             alt="{{ $item->wisata->nama_wisata }}" 
                             style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ $item->wisata->kategori }}</span>
                            <h5 class="card-title text-truncate">{{ $item->wisata->nama_wisata }}</h5>
                            <p class="card-text text-muted small mb-1">
                                <i class="bi bi-geo-alt"></i> {{ $item->wisata->kabupaten_kota }}, {{ $item->wisata->provinsi }}
                            </p>
                        </div>
                        
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center pb-3">
                            <a href="{{ route('wisata.show', $item->wisata->wisata_id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            <button class="btn btn-sm btn-danger btn-toggle-bookmark" data-id="{{ $item->wisata->wisata_id }}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-toggle-bookmark');
    
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const wisataId = this.getAttribute('data-id');
            const cardElement = document.getElementById(`bookmark-card-${wisataId}`);

            fetch("{{ route('bookmark.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ wisata_id: wisataId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'removed') {
                    if (cardElement) {
                        cardElement.remove();
                    }
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endsection