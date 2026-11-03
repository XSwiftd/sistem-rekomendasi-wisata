<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisatas';
    protected $primaryKey = 'wisata_id';
    public $incrementing = false; // Karena ID berasal dari CSV

    protected $fillable = [
        'wisata_id',
        'nama_wisata',
        'kabupaten_kota',
        'provinsi',
        'kategori',
        'tags',
        'deskripsi',
        'estimasi_biaya',
        'tingkat_kesulitan',
        'latitude',
        'longitude',
        'gambar',
    ];

    public function interaksis()
    {
        return $this->hasMany(Interaksi::class, 'wisata_id', 'wisata_id');
    }
}