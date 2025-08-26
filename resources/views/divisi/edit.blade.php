@extends('layouts.app')

@section('title', 'Edit Divisi')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Divisi</h2>

        <form action="{{ route('divisi.update', $divisi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Divisi</label>
                <input type="text" class="form-control" name="nama" id="nama" value="{{ $divisi->nama }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('divisi.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
