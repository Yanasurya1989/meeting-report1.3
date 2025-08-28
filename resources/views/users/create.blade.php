@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="container">
        <h2 class="mb-4">Tambah User</h2>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Sub Divisi</label>

                @php
                    // Bangun grup sub divisi berdasarkan data yang dikirim controller:
                    // - Jika controller mengirim $divisis (Divisi dengan relasi subDivisis), gunakan langsung.
                    // - Jika controller mengirim $subDivisis (daftar SubDivisi dengan relasi divisi), kelompokkan berdasarkan nama divisi.
                    $groups = [];

                    if (isset($divisis) && $divisis->isNotEmpty()) {
                        foreach ($divisis as $d) {
                            // pastikan subDivisis ada
                            $groups[$d->nama] = $d->subDivisis ?? collect();
                        }
                    } elseif (isset($subDivisis) && $subDivisis->isNotEmpty()) {
                        foreach ($subDivisis as $s) {
                            $divName = $s->divisi->nama ?? 'Lainnya';
                            if (!isset($groups[$divName])) {
                                $groups[$divName] = collect();
                            }
                            $groups[$divName]->push($s);
                        }
                    }
                    $oldSelected = old('sub_divisi_id', []);
                @endphp

                <select name="sub_divisi_id[]" class="form-select @error('sub_divisi_id') is-invalid @enderror" multiple
                    size="8">
                    @if (!empty($groups))
                        @foreach ($groups as $divName => $subs)
                            <optgroup label="{{ $divName }}">
                                @foreach ($subs as $s)
                                    <option value="{{ $s->id }}"
                                        {{ in_array($s->id, $oldSelected) ? 'selected' : '' }}>
                                        {{ $s->nama }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    @else
                        <option disabled>Tidak ada sub divisi</option>
                    @endif
                </select>

                @error('sub_divisi_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mt-1">Bisa pilih lebih dari satu (Ctrl/Command + klik).</small>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
