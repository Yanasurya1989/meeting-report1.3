@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Jadwal Meeting</h2>
        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Divisi</label>
                <select name="divisi_id" id="divisi_id" class="form-control">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}">{{ $divisi->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Sub Divisi</label>
                <select name="sub_divisi_id" id="sub_divisi_id" class="form-control">
                    <option value="">-- Pilih Sub Divisi --</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Hari</label>
                <select name="hari" class="form-control">
                    <option>Senin</option>
                    <option>Selasa</option>
                    <option>Rabu</option>
                    <option>Kamis</option>
                    <option>Jumat</option>
                    <option>Sabtu</option>
                    <option>Minggu</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control">
            </div>

            <div class="mb-3">
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <script>
        const divisis = @json($divisis);
        const divisiSelect = document.getElementById('divisi_id');
        const subDivisiSelect = document.getElementById('sub_divisi_id');

        divisiSelect.addEventListener('change', function() {
            let id = this.value;
            subDivisiSelect.innerHTML = '<option value="">-- Pilih Sub Divisi --</option>';
            if (id) {
                let selectedDivisi = divisis.find(d => d.id == id);
                selectedDivisi.sub_divisis.forEach(sd => {
                    subDivisiSelect.innerHTML += `<option value="${sd.id}">${sd.nama}</option>`;
                });
            }
        });
    </script>
@endsection
