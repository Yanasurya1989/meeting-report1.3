@extends('layouts.app')

@section('title', 'Edit Sub Divisi')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Sub Divisi</h2>

        <form action="{{ route('subdivisi.update', $subdivisi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="divisi_id" class="form-label">Divisi</label>
                <select name="divisi_id" id="divisi_id" class="form-control" required>
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}" {{ $divisi->id == $subdivisi->divisi_id ? 'selected' : '' }}>
                            {{ $divisi->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Sub Divisi</label>
                <input type="text" class="form-control" name="nama" id="nama" value="{{ $subdivisi->nama }}"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('subdivisi.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
