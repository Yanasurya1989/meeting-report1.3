<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Divisi extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    // Relasi ke SubDivisi
    public function subDivisis()
    {
        return $this->hasMany(SubDivisi::class);
    }
}
