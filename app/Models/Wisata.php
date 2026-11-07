<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisatas';
    protected $primaryKey = 'wisata_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_wisata',
        'deskripsi',
        'kategori',
        'provinsi',
        'kabupaten_kota',
        'estimasi_biaya',
        'tingkat_kesulitan',
        'latitude',
        'longitude',
        'gambar',
        'tags',
    ];

    /**
     * Relasi ke tabel interaksis (Ulasan / Rating)
     */
    public function interaksis()
    {
        return $this->hasMany(Interaksi::class, 'wisata_id', 'wisata_id');
    }

    /**
     * Scope untuk menghitung rata-rata rating & total ulasan secara dinamis
     */
    public function scopeWithAvgRating($query)
    {
        return $query->withAvg('interaksis as avg_rating', 'rating')
                     ->withCount('interaksis as total_ulasan');
    }

    /**
     * Scope untuk pencarian kata kunci
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_wisata', 'like', "%{$keyword}%")
              ->orWhere('kabupaten_kota', 'like', "%{$keyword}%")
              ->orWhere('provinsi', 'like', "%{$keyword}%")
              ->orWhere('tags', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%");
        });
    }
}