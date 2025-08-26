@extends('layouts.app')

@section('title', 'Daftar Divisi')

@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Divisi</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('divisi.create') }}" class="btn btn-primary mb-3">Tambah Divisi</a>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Divisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($divisis as $index => $divisi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $divisi->nama }}</td>
                                <td>
                                    <a href="{{ route('divisi.edit', $divisi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('divisi.destroy', $divisi->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada divisi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
