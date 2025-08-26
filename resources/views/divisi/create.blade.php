@extends('layouts.app')

@section('title', 'Tambah Divisi')

@section('content')
    <div class="container">
        <h2 class="mb-4">Tambah Divisi</h2>

        <form action="{{ route('divisi.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Divisi</label>
                <input type="text" class="form-control" name="nama" id="nama" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('divisi.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
