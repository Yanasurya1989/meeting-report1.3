<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubDivisi extends Model
{
    use HasFactory;

    protected $fillable = ['divisi_id', 'nama'];

    // Relasi ke Divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    // Relasi ke User lewat pivot peserta_sub_divisi
    public function users()
    {
        return $this->belongsToMany(User::class, 'peserta_sub_divisi', 'sub_divisi_id', 'user_id');
    }
}
