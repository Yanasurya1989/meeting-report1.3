<?php

namespace App\Http\Controllers;

use App\Models\JadwalMeeting;
use App\Models\Divisi;
use App\Models\SubDivisi;
use Illuminate\Http\Request;

class JadwalMeetingController extends Controller
{
    public function index()
    {
        $jadwal = JadwalMeeting::with(['divisi', 'subDivisi'])->get();
        return view('jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $divisis = Divisi::with('subDivisis')->get();
        return view('jadwal.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'sub_divisi_id' => 'required|exists:sub_divisis,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable',
        ]);

        JadwalMeeting::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal meeting berhasil ditambahkan.');
    }

    public function edit(JadwalMeeting $jadwal)
    {
        $divisis = Divisi::with('subDivisis')->get();
        return view('jadwal.edit', compact('jadwal', 'divisis'));
    }

    public function update(Request $request, JadwalMeeting $jadwal)
    {
        $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'sub_divisi_id' => 'required|exists:sub_divisis,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal meeting berhasil diperbarui.');
    }

    public function destroy(JadwalMeeting $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal meeting berhasil dihapus.');
    }
}
