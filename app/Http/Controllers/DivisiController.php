<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\SubDivisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function getDivisi()
    {
        return response()->json(Divisi::select('id', 'nama')->get());
    }

    public function getSubDivisi($id)
    {
        $divisi = Divisi::with('subDivisis')->findOrFail($id);

        if ($divisi->subDivisis->isEmpty()) {
            // Divisi tanpa sub divisi, anggap ada "peserta langsung"
            return response()->json([
                'subdivisi' => [],
                'peserta' => [] // kalau memang tidak ada pivot, bisa kosong
            ]);
        }

        return response()->json([
            'subdivisi' => $divisi->subDivisis()->select('id', 'nama')->get(),
            'peserta' => []
        ]);
    }

    public function getPeserta($id)
    {
        $subDivisi = SubDivisi::with('users')->findOrFail($id);

        return response()->json($subDivisi->users()->select('users.id', 'users.name')->get());
    }

    public function index()
    {
        $divisis = Divisi::all();
        return view('divisi.index', compact('divisis'));
    }

    public function create()
    {
        return view('divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Divisi::create($request->all());

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Divisi $divisi)
    {
        return view('divisi.edit', compact('divisi'));
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $divisi->update($request->all());

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
