<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaksi extends Model
{
    use HasFactory;

    protected $table = 'interaksis';
    protected $primaryKey = 'interaksi_id';
    public $incrementing = false; // Karena ID berasal dari CSV

    protected $fillable = [
        'interaksi_id',
        'user_id',
        'wisata_id',
        'jenis_interaksi',
        'rating',
        'ulasan',
        'tanggal_interaksi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function wisata()
    {
        return $this->belongsTo(Wisata::class, 'wisata_id', 'wisata_id');
    }
}