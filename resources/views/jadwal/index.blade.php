@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Jadwal Meeting</h2>
    <a href="{{ route('jadwal.create') }}" class="btn btn-primary mb-3">Tambah Jadwal</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Divisi</th>
                <th>Sub Divisi</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal as $j)
            <tr>
                <td>{{ $j->divisi->nama }}</td>
                <td>{{ $j->subDivisi->nama }}</td>
                <td>{{ $j->hari }}</td>
                <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                <td>
                    <a href="{{ route('jadwal.edit', $j->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
