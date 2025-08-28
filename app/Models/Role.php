<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles'; // pastikan sama dengan nama tabel di database
    protected $fillable = [
        'name',
        'description', // kalau di tabel kamu ada deskripsi
    ];

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
