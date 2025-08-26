<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Not to land
    let pointCount = 1;
    const wrapper = document.getElementById('notulen-wrapper');
    const addBtn = document.getElementById('add-point');

    addBtn.addEventListener('click', () => {
        pointCount++;
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');
        div.innerHTML = `
            <span class="input-group-text">Point ${pointCount}</span>
            <input type="text" name="notulen[]" class="form-control" placeholder="Isi notulen point ${pointCount}" required>
        `;
        wrapper.appendChild(div);
    });


    // Divisi Dinamis
    $(function() {
        // load divisi saat halaman dibuka
        $.get("{{ route('divisi.list') }}", function(data) {
            data.forEach(d => {
                $('#divisi').append(`<option value="${d.id}">${d.nama}</option>`);
            });
        });

        // kalau pilih divisi
        $('#divisi').on('change', function() {
            let divisiId = $(this).val();
            $('#sub_divisi').empty().append('<option value="">-- Pilih Sub Divisi --</option>');
            $('#pesertaContainer').html(
                '<p class="text-muted">Silakan pilih divisi atau sub divisi terlebih dahulu.</p>');

            if (divisiId) {
                $.get(`/divisi/${divisiId}/subdivisi`, function(res) {
                    if (res.subdivisi.length > 0) {
                        $('#subDivisiWrapper').show();
                        res.subdivisi.forEach(s => {
                            $('#sub_divisi').append(
                                `<option value="${s.id}">${s.nama}</option>`);
                        });
                    } else {
                        $('#subDivisiWrapper').hide();
                        renderPeserta(res.peserta);
                    }
                });
            }
        });

        // kalau pilih sub divisi
        $('#sub_divisi').on('change', function() {
            let subId = $(this).val();
            $('#pesertaContainer').html('<p class="text-muted">Loading peserta...</p>');

            if (subId) {
                $.get(`/subdivisi/${subId}/peserta`, function(data) {
                    renderPeserta(data);
                });
            }
        });

        // centang semua
        $('#checkAll').on('change', function() {
            $('input[name="peserta[]"]').prop('checked', $(this).is(':checked'));
        });

        function renderPeserta(peserta) {
            if (peserta.length === 0) {
                $('#pesertaContainer').html('<p class="text-muted">Tidak ada peserta.</p>');
                return;
            }

            let html = '';
            peserta.forEach(p => {
                html += `
            <div class="col">
                <div class="form-check">
                    <input type="checkbox" name="peserta[]" value="${p.id}" class="form-check-input" id="peserta_${p.id}">
                    <label for="peserta_${p.id}" class="form-check-label">${p.name}</label>
                </div>
            </div>`;
            });
            $('#pesertaContainer').html(html);
        }
    });

    // === Waktu otomatis (Hari, Tanggal, Jam) ===
    const waktuField = document.getElementById('waktu');

    function setWaktuAwal() {
        const now = new Date();
        const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const months = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const dayName = days[now.getDay()];
        const date = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();

        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let seconds = now.getSeconds().toString().padStart(2, '0');

        // Format: Hari, 22 Agustus 2025 - 23:20:11
        const formatted = `${dayName}, ${date} ${month} ${year} - ${hours}:${minutes}:${seconds}`;

        waktuField.value = formatted;
    }

    // Jalankan sekali saat halaman selesai dimuat
    window.addEventListener('DOMContentLoaded', setWaktuAwal);

    // === Kamera ===
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photoInput = document.getElementById('capture_image');
    const preview = document.getElementById('preview');

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            video.srcObject = stream;
            setTimeout(capturePhoto, 2000);
        } catch (err) {
            alert('Tidak bisa mengakses kamera');
        }
    }

    // function capturePhoto() {
    //     canvas.width = video.videoWidth;
    //     canvas.height = video.videoHeight;
    //     canvas.getContext('2d').drawImage(video, 0, 0);
    //     const imgData = canvas.toDataURL('image/png');
    //     photoInput.value = imgData;
    //     preview.src = imgData;
    // }

    function capturePhoto() {
        const ctx = canvas.getContext('2d');

        // target lebar 800px (hemat), tinggi ikut rasio video
        const targetW = 800;
        const ratio = video.videoWidth / video.videoHeight || (4 / 3);
        const targetH = Math.round(targetW / ratio);

        canvas.width = targetW;
        canvas.height = targetH;

        // gambar ke canvas dengan ukuran baru
        ctx.drawImage(video, 0, 0, targetW, targetH);

        // export ke JPEG kualitas 0.8 (80%)
        const imgData = canvas.toDataURL('image/jpeg', 0.8);

        photoInput.value = imgData;
        preview.src = imgData;
    }

    window.addEventListener('DOMContentLoaded', startCamera);
    document.getElementById('captureAgain').addEventListener('click', capturePhoto);
</script>
