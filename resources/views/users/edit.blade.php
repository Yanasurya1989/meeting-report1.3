@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit User</h2>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="sub_divisis" class="form-label">Sub Divisi</label>
                <select name="sub_divisis[]" class="form-select" multiple>
                    @foreach ($subDivisis as $sd)
                        <option value="{{ $sd->id }}" {{ in_array($sd->id, $selectedSubDivisis) ? 'selected' : '' }}>
                            {{ $sd->divisi->nama }} - {{ $sd->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
