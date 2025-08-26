@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah User</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <!-- Nama -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- Divisi -->
                <div class="mb-3">
                    <label for="division_id" class="form-label">Divisi</label>
                    <select name="division_id" class="form-select" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sub Divisi (dinamis) -->
                <div class="mb-3">
                    <label class="form-label">Sub Divisi</label>
                    <div id="sub-division-wrapper">
                        <div class="input-group mb-2">
                            <select name="sub_divisions[]" class="form-select" required>
                                <option value="">-- Pilih Sub Divisi --</option>
                                @foreach ($subdivisions as $subdivision)
                                    <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-danger remove-subdivision">Hapus</button>
                        </div>
                    </div>
                    <button type="button" id="add-subdivision" class="btn btn-outline-primary btn-sm">
                        + Tambah Sub Divisi
                    </button>
                </div>

                <!-- Tombol Simpan -->
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const wrapper = document.getElementById("sub-division-wrapper");
            const addBtn = document.getElementById("add-subdivision");

            addBtn.addEventListener("click", function() {
                let div = document.createElement("div");
                div.classList.add("input-group", "mb-2");

                div.innerHTML = `
            <select name="sub_divisions[]" class="form-select" required>
                <option value="">-- Pilih Sub Divisi --</option>
                @foreach ($subdivisions as $subdivision)
                    <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-outline-danger remove-subdivision">Hapus</button>
        `;

                wrapper.appendChild(div);
            });

            wrapper.addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-subdivision")) {
                    e.target.closest(".input-group").remove();
                }
            });
        });
    </script>
@endpush
