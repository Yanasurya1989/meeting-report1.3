<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SubDivisi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

    public function storeKembalikanIniJikaBawahGagal(Request $request)
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

    public function updateKembalikanIniJikaBawahGagal(Request $request, User $user)
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'sub_divisi_id'   => ['nullable', 'array'],
            'sub_divisi_id.*' => ['exists:sub_divisis,id'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']), // ✅ Hash password
            ]);

            if (!empty($validated['sub_divisi_id'])) {
                $user->subDivisis()->sync($validated['sub_divisi_id']);
            }
        });

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:users,email,{$user->id}"],
            'password' => ['nullable', 'min:6'],
            'sub_divisi_id'   => ['nullable', 'array'],
            'sub_divisi_id.*' => ['exists:sub_divisis,id'],
        ]);

        DB::transaction(function () use ($request, $user, $validated) {
            $data = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']); // ✅ Hash password baru
            }

            $user->update($data);

            $user->subDivisis()->sync($validated['sub_divisi_id'] ?? []);
        });

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }
}
