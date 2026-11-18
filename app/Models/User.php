<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false; // Karena ID berasal dari CSV

    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'usia',
        'jenis_kelamin',
        'domisili',
        'minat_kategori',
        'preferensi_anggaran',
        'preferensi_kesulitan',
    ];

    public function interaksis()
    {
        return $this->hasMany(Interaksi::class, 'user_id', 'user_id');
    }
}