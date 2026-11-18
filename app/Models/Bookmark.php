<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = 'bookmarks';
    protected $primaryKey = 'bookmark_id';
    public $timestamps = false; // Menggunakan created_at bawaan MySQL

    protected $fillable = [
        'user_id',
        'wisata_id',
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