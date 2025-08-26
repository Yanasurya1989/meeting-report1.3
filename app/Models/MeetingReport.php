<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MeetingReport extends Model
{
    protected $fillable = [
        'notulen',
        'divisi',
        'sub_divisi',
        'peserta',
        'capture_image',
        'waktu_rapat'
    ];

    protected $casts = [
        'notulen' => 'array',
        'peserta' => 'array',
        'waktu_rapat' => 'datetime',
    ];


    // relasi ke tabel divisi
    public function divisiRelasi()
    {
        return $this->belongsTo(Divisi::class, 'divisi');
    }

    // relasi ke tabel sub divisi
    public function subDivisiRelasi()
    {
        return $this->belongsTo(SubDivisi::class, 'sub_divisi');
    }

    // relasi ke tabel user
    public function pesertaUsers()
    {
        return $this->belongsToMany(User::class, 'meeting_report_user')
            ->withTimestamps();
    }



    public function getFormattedWaktuRapatAttribute()
    {
        if (!$this->waktu_rapat) {
            return null;
        }
        return Carbon::parse($this->waktu_rapat)->translatedFormat('l, d F Y - H:i:s');
    }
}
