@extends('layouts.app')

@section('title', 'Form Laporan Meeting')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-4 rounded shadow">
        <h1 class="h4 mb-3">Form Laporan Meeting</h1>

        {{-- Error handling --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('meeting.store') }}" method="POST">
            @csrf

            {{-- Notulen --}}
            <div class="mb-3">
                <label class="form-label">Notulen</label>
                <div id="notulen-wrapper">
                    <div class="input-group mb-2">
                        <span class="input-group-text">Point 1</span>
                        <input type="text" name="notulen[]" class="form-control" placeholder="Isi notulen point 1"
                            required>
                    </div>
                </div>
                <button type="button" id="add-point" class="btn btn-sm btn-success mt-2">
                    + Tambah Point
                </button>
            </div>

            {{-- Divisi --}}
            <div class="mb-3">
                <label for="divisi" class="form-label">Pilih Unit</label>
                <select id="divisi" name="divisi" class="form-select">
                    <option value="">-- Pilih Unit --</option>
                </select>
            </div>

            {{-- Sub Divisi --}}
            <div class="mb-3" id="subDivisiWrapper" style="display:none;">
                <label for="sub_divisi" class="form-label">Pilih Sub Unit</label>
                <select id="sub_divisi" name="sub_divisi" class="form-select">
                    <option value="">-- Pilih Sub Unit --</option>
                </select>
            </div>

            {{-- Peserta --}}
            <div class="mb-3">
                <label class="form-label">Daftar Peserta</label>
                <div class="form-check mb-2">
                    <input type="checkbox" id="checkAll" class="form-check-input">
                    <label for="checkAll" class="form-check-label fw-bold">Centang Semua Peserta</label>
                </div>
                <div id="pesertaContainer" class="row row-cols-2 g-1">
                    <p class="text-muted">Silakan pilih divisi atau sub divisi terlebih dahulu.</p>
                </div>
            </div>

            {{-- Kamera --}}
            <div class="mb-3">
                <label class="form-label">Ambil Foto Otomatis</label>
                <div class="d-flex gap-3">
                    <video id="video" autoplay playsinline class="border rounded"
                        style="width:200px;height:150px;"></video>
                    <img id="preview" class="border rounded" style="width:200px;height:150px;" alt="Hasil Capture">
                </div>
                <canvas id="canvas" class="d-none"></canvas>
                <input type="hidden" name="capture_image" id="capture_image">
                <button type="button" id="captureAgain" class="btn btn-secondary btn-sm mt-2">Ambil Ulang Foto</button>
            </div>

            {{-- Waktu --}}
            <div class="mb-3">
                <label for="waktu" class="form-label">Waktu</label>
                <input type="text" id="waktu" name="waktu_rapat" readonly class="form-control">
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        </form>
    </div>
@endsection

@push('styles')
    {{-- Kalau mau tambahan CSS khusus halaman ini --}}
@endpush

@push('scripts')
    @include('meeting.partials.scripts')
@endpush
