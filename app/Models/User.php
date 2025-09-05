<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke SubDivisi lewat pivot peserta_sub_divisi
    public function subDivisis()
    {
        return $this->belongsToMany(SubDivisi::class, 'peserta_sub_divisi', 'user_id', 'sub_divisi_id');
    }

    // User.php
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function isDirekturAtauYayasan()
    {
        $divisiUtama = $this->divisi?->nama;
        $subDivisi = $this->subDivisis->pluck('divisi.nama');

        return in_array($divisiUtama, ['Direktur', 'Yayasan']) ||
            $subDivisi->contains('Direktur') ||
            $subDivisi->contains('Yayasan');
    }
}
