<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Divisi;
use App\Models\SubDivisi;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        // Data asli dari HTML
        $data = [
            "Yayasan" => [
                "peserta" => ["Hilda", "Sati", "Erni", "Aan", "Thia", "Dadah", "Dindin", "Hendi"]
            ],
            "Bidang 1" => [
                "subDivisi" => [
                    "Koord PU" => ["Andi", "Budi", "Citra"],
                    "Kls International" => ["Rina", "Susi", "Bayu"]
                ]
            ],
            "Bidang 2" => [
                "subDivisi" => [
                    "Rapat dg tim Sarpras" => ["Sari", "Agus", "Fikri"],
                    "Rapat dg tim UUS" => ["Ayu", "Bambang", "Dewi"],
                    "Rapat dg tim AJS" => ["Lina", "Tono", "Rizki"],
                    "Rapat dg tim CS" => ["Vina", "Rudi", "Nanda"],
                    "Koordinasi dg tim BSP" => ["Putri", "Adit", "Wulan"],
                    "Koordinasi dg tim QFC" => ["Samsul", "Gita", "Rama"],
                ]
            ],
            "Bidang 3" => [
                "subDivisi" => [
                    "PPDB" => ["Eka", "Fina", "Sandi"],
                    "Medsos Siswa" => ["Heri", "Lusi", "Dani"],
                    "Medsos Sekolah" => ["Yani", "Iwan", "Mira"],
                ]
            ],
            "Bidang 4" => [
                "peserta" => ["Anton", "Bella", "Caca", "Dono", "Evi"]
            ],
            "SD" => [
                "subDivisi" => [
                    "Rapat KS-Wakasek" => ["Wahyu", "Nina", "Beni"],
                    "Rapat Managemen" => ["Siti", "Fajar", "Tari"],
                    "Rapat KS-Koord. Program Unggulan" => ["Udin", "Cici", "Toto"],
                    "Rapat KS-Korjen" => ["Vivi", "Heri", "Zaki"],
                    "Rapat KS/Wakasek-BK" => ["Bunga", "Rino", "Galih"],
                    "Rapat KS/Wakasek-Koord.Wusho" => ["Gina", "Hana", "Rio"],
                    "Rapat KS/Wakasek-Koord.Kelulusan" => ["Hani", "Jaka", "Miko"],
                    "Rapat Tim Bahasa Arab" => ["Zahra", "Fahri", "Lutfi"],
                    "Rapat Tim Bahasa Inggris" => ["Sari", "Andra", "Bima"],
                    "Rapat Tim AQ" => ["Yoga", "Hilda", "Putra"],
                    "Rapat Tim MTK" => ["Rini", "Edo", "Lala"],
                    "Rapat Tim PAI" => ["Tina", "Raka", "Deni"],
                    "Rapat Umum" => ["Oka", "Mila", "Faisal"],
                ]
            ],
            "SMP" => [
                "subDivisi" => [
                    "Rapat Unit" => ["Fani", "Seno", "Tari"],
                    "Rapat PKS" => ["Indra", "Putri", "Bayu"],
                    "Rapat Manajemen Level" => ["Ari", "Nita", "Gilang"],
                    "Rapat Al-Quran" => ["Umar", "Hana", "Fikri"],
                    "Rapat Bahasa Arab" => ["Zaki", "Sari", "Budi"],
                    "Rapat Bahasa Inggris" => ["Lina", "Eko", "Rina"],
                    "Rapat Tim Kesiswaan" => ["Rizki", "Mira", "Tono"],
                    "Rapat Mata Pelajaran" => ["Adi", "Vivi", "Bagas"],
                    "Rapat KS-BK" => ["Dewi", "Candra", "Wawan"],
                    "Rapat KS-Kurikulum" => ["Yudi", "Fitri", "Andi"],
                    "Rapat KS-Kesiswaan" => ["Fani", "Tata", "Herman"],
                ]
            ],
            "SMA" => [
                "subDivisi" => [
                    "PKS Kurikulum" => ["Rama", "Fahmi", "Nina"],
                    "PKS Kesiswaan" => ["Bagus", "Anisa", "Tio"],
                    "Koordinator Program Unggulan" => ["Wulan", "Ardi", "Sari"],
                    "Tim literasi" => ["Rudi", "Tika", "Gilang"],
                    "Tim UTBK" => ["Evi", "Heri", "Sandi"],
                    "Tim Evakuasi" => ["Bayu", "Lina", "Rama"],
                    "Tim pengembangan kurikulum" => ["Vina", "Hadi", "Putra"],
                    "Tim sarana" => ["Reno", "Gita", "Wawan"],
                    "PJ organisasi" => ["Sinta", "Eko", "Dewi"],
                    "PJ ibadah" => ["Yusuf", "Hana", "Andri"],
                    "PJ media" => ["Zahra", "Fani", "Rendi"],
                    "PJ lomba" => ["Dina", "Adi", "Putri"],
                    "PJ ekskul" => ["Rizki", "Bela", "Hendra"],
                    "BK" => ["Faisal", "Mira", "Gilang"],
                ]
            ]
        ];

        // Loop divisi → sub divisi → peserta
        foreach ($data as $divisiNama => $isi) {
            $divisi = Divisi::create(['nama' => $divisiNama]);

            // Kalau langsung punya peserta
            if (isset($isi['peserta'])) {
                foreach ($isi['peserta'] as $nama) {
                    $user = User::firstOrCreate(
                        ['email' => strtolower($nama) . '@mail.com'],
                        ['name' => $nama, 'password' => bcrypt('password')]
                    );

                    // Buat sub divisi default = nama divisi
                    $subDivisi = SubDivisi::firstOrCreate(
                        ['divisi_id' => $divisi->id, 'nama' => $divisiNama]
                    );

                    $user->subDivisis()->syncWithoutDetaching([$subDivisi->id]);
                }
            }

            // Kalau punya sub divisi
            if (isset($isi['subDivisi'])) {
                foreach ($isi['subDivisi'] as $subNama => $pesertas) {
                    $subDivisi = SubDivisi::create([
                        'divisi_id' => $divisi->id,
                        'nama' => $subNama
                    ]);

                    foreach ($pesertas as $nama) {
                        $user = User::firstOrCreate(
                            ['email' => strtolower($nama) . '@mail.com'],
                            ['name' => $nama, 'password' => bcrypt('password')]
                        );

                        $user->subDivisis()->syncWithoutDetaching([$subDivisi->id]);
                    }
                }
            }
        }
    }
}
