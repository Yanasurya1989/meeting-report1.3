@extends('layouts.app')

@section('title', 'Yayasan Amal Insan Rabbani')

@section('content')
    <div class="container">
        <h1 class="h4 mb-3">Daftar Laporan Meeting</h1>

        {{-- Filter --}}
        <form method="GET" action="{{ route('meeting.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Divisi</label>
                <input type="text" name="divisi" value="{{ request('divisi') }}" class="form-control"
                    placeholder="Isi Divisi">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sub Divisi</label>
                <input type="text" name="sub_divisi" value="{{ request('sub_divisi') }}" class="form-control"
                    placeholder="Isi Sub Divisi">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Filter</button>
                @if (count($reports) > 0)
                    <a href="{{ route('meeting.export', request()->query()) }}" class="btn btn-success">
                        Export CSV
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Divisi</th>
                        <th>Sub Divisi</th>
                        <th>Peserta</th>
                        <th>Notulen</th>
                        <th>Waktu Rapat</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $report->divisiRelasi?->nama ?? '-' }}</td>
                            <td>{{ $report->subDivisiRelasi?->nama ?? '-' }}</td>
                            <td>
                                @forelse($report->pesertaUsers as $peserta)
                                    <span class="badge bg-primary">{{ $peserta->name }}</span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </td>
                            <td>
                                <ul class="mb-0">
                                    @foreach ($report->notulen ?? [] as $point)
                                        <li>{{ $point }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $report->formatted_waktu_rapat ?? 'Tidak ada data' }}</td>
                            <td>
                                @if ($report->capture_image)
                                    <img src="{{ asset('storage/' . $report->capture_image) }}" alt="Foto Meeting"
                                        class="img-thumbnail" style="max-width:120px; cursor:pointer" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="{{ asset('storage/' . $report->capture_image) }}">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('meeting.edit', $report->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('meeting.destroy', $report->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada laporan</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    {{-- Modal Preview Foto --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Foto Meeting">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
