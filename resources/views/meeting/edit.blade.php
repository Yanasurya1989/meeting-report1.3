@extends('layouts.app')

@section('title', 'Edit Laporan Meeting')

@section('content')
    <div class="container">
        <h1 class="h4 mb-3">Edit Laporan Meeting</h1>

        <form action="{{ route('meeting.update', $report->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Notulen --}}
            <div class="mb-3">
                <label class="form-label">Notulen</label>
                <textarea name="notulen" class="form-control" rows="4">{{ old('notulen', implode("\n", $report->notulen ?? [])) }}</textarea>
                <small class="text-muted">Pisahkan setiap poin notulen dengan baris baru.</small>

            </div>

            {{-- Divisi --}}
            <div class="mb-3">
                <label class="form-label">Divisi</label>
                <input type="text" name="divisi" class="form-control" value="{{ old('divisi', $report->divisi) }}">
            </div>

            {{-- Sub Divisi --}}
            <div class="mb-3">
                <label class="form-label">Sub Divisi</label>
                <input type="text" name="sub_divisi" class="form-control"
                    value="{{ old('sub_divisi', $report->sub_divisi) }}">
            </div>

            {{-- Peserta --}}
            <div class="mb-3">
                <label class="form-label">Peserta</label>
                <select name="peserta[]" class="form-select" multiple>
                    @foreach (\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}"
                            {{ in_array($user->id, old('peserta', $report->pesertaUsers->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Tekan Ctrl / Command untuk memilih lebih dari satu peserta.</small>
            </div>

            {{-- Waktu Rapat --}}
            <div class="mb-3">
                <label class="form-label">Waktu Rapat</label>
                <input type="text" name="waktu_rapat" class="form-control"
                    value="{{ old('waktu_rapat', $report->formatted_waktu_rapat) }}">
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label class="form-label">Foto Meeting</label><br>
                @if ($report->capture_image)
                    <img src="{{ asset('storage/' . $report->capture_image) }}" class="img-thumbnail mb-2"
                        style="max-width:150px;">
                @endif
                <input type="file" name="capture_image" class="form-control">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('meeting.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
