<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\SubDivisi;
use App\Models\User;
use Illuminate\Http\Request;

class MeetingApiController extends Controller
{
    // Ambil semua divisi
    public function getDivisi()
    {
        $divisi = Divisi::all(['id', 'nama']);
        return response()->json($divisi);
    }

    // Ambil sub divisi berdasarkan divisi
    public function getSubDivisi($divisiId)
    {
        $subDivisi = SubDivisi::where('divisi_id', $divisiId)->get(['id', 'nama']);
        return response()->json($subDivisi);
    }

    // Ambil peserta (user) berdasarkan divisi / sub divisi
    public function getPeserta(Request $request)
    {
        $query = User::query();

        if ($request->has('divisi_id')) {
            $query->whereHas('divisis', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        if ($request->has('sub_divisi_id')) {
            $query->whereHas('subDivisis', function ($q) use ($request) {
                $q->where('sub_divisi_id', $request->sub_divisi_id);
            });
        }

        return response()->json(
            $query->get(['id', 'name'])
        );
    }
}
