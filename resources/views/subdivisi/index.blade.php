@extends('layouts.app')

@section('title', 'Daftar Sub Divisi')

@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Sub Divisi</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('subdivisi.create') }}" class="btn btn-primary mb-3">Tambah Sub Divisi</a>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Sub Divisi</th>
                            <th>Divisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subDivisis as $index => $sub)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sub->nama }}</td>
                                <td>{{ $sub->divisi->nama ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('subdivisi.edit', $sub->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('subdivisi.destroy', $sub->id) }}" method="POST"
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
                                <td colspan="4" class="text-center">Belum ada Sub Divisi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
