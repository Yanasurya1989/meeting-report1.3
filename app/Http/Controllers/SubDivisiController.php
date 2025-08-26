<?php

namespace App\Http\Controllers;

use App\Models\SubDivisi;
use App\Models\Divisi;
use Illuminate\Http\Request;

class SubDivisiController extends Controller
{
    public function index()
    {
        $subDivisis = SubDivisi::with('divisi')->get();
        return view('subdivisi.index', compact('subDivisis'));
    }

    public function create()
    {
        $divisis = Divisi::all();
        return view('subdivisi.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'nama' => 'required|string|max:255',
        ]);

        SubDivisi::create($request->all());

        return redirect()->route('subdivisi.index')->with('success', 'Sub Divisi berhasil ditambahkan.');
    }

    public function edit(SubDivisi $subdivisi)
    {
        $divisis = Divisi::all();
        return view('subdivisi.edit', compact('subdivisi', 'divisis'));
    }

    public function update(Request $request, SubDivisi $subdivisi)
    {
        $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'nama' => 'required|string|max:255',
        ]);

        $subdivisi->update($request->all());

        return redirect()->route('subdivisi.index')->with('success', 'Sub Divisi berhasil diperbarui.');
    }

    public function destroy(SubDivisi $subdivisi)
    {
        $subdivisi->delete();
        return redirect()->route('subdivisi.index')->with('success', 'Sub Divisi berhasil dihapus.');
    }

    public function show(SubDivisi $subdivisi)
    {
        return redirect()->route('subdivisi.index');
    }
}
