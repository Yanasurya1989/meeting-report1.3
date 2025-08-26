<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SubDivisi;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('subDivisis.divisi')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $subDivisis = SubDivisi::with('divisi')->get();
        return view('users.create', compact('subDivisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'sub_divisis' => 'array'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('sub_divisis')) {
            $user->subDivisis()->sync($request->sub_divisis);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $subDivisis = SubDivisi::with('divisi')->get();
        $selectedSubDivisis = $user->subDivisis->pluck('id')->toArray();

        return view('users.edit', compact('user', 'subDivisis', 'selectedSubDivisis'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'sub_divisis' => 'array'
        ]);

        $data = $request->only('name', 'email');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->has('sub_divisis')) {
            $user->subDivisis()->sync($request->sub_divisis);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
