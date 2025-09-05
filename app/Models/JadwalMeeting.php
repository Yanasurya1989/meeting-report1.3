<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'divisi_id',
        'sub_divisi_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function subDivisi()
    {
        return $this->belongsTo(SubDivisi::class);
    }
}
