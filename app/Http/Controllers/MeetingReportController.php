<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MeetingReport;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class MeetingReportController extends Controller
{
    public function create()
    {
        return view('meeting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'notulen'       => 'required|array|min:1',
            'notulen.*'     => 'required|string',
            'divisi'        => 'required|string',
            'sub_divisi'    => 'nullable|string',
            'peserta'       => 'nullable|array',
            'waktu_rapat'   => 'required|string',
            'capture_image' => 'nullable|string',
        ]);

        $photoPath = null;

        // === Resize + compress image (jika ada) ===
        if ($request->filled('capture_image')) {
            try {
                $dataUrl = $request->capture_image;
                if (str_contains($dataUrl, ',')) {
                    [$meta, $base64] = explode(',', $dataUrl, 2);
                } else {
                    $base64 = $dataUrl;
                }
                $binary = base64_decode($base64);

                $image = \Image::make($binary)
                    ->orientate()
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('jpg', 80);

                $filename = 'meeting_' . now()->format('Ymd_His') . '_' . \Str::random(6) . '.jpg';
                $dir = 'meeting_photos';

                \Storage::disk('public')->put($dir . '/' . $filename, (string) $image);
                $photoPath = $dir . '/' . $filename;
            } catch (\Throwable $e) {
                $photoPath = null;
            }
        }

        // === Convert waktu rapat ===
        $waktuString = preg_replace('/^[A-Za-zÀ-ÿ]+,\s*/u', '', $request->waktu_rapat);
        $waktuString = $this->convertIndonesianMonthToEnglish($waktuString);

        $waktuRapat = \Carbon\Carbon::createFromFormat('d F Y - H:i:s', $waktuString, 'Asia/Jakarta')
            ->format('Y-m-d H:i:s');

        // === Simpan rapat ===
        $meeting = MeetingReport::create([
            'notulen'       => $request->notulen,
            'divisi'        => $request->divisi,
            'sub_divisi'    => $request->sub_divisi,
            'capture_image' => $photoPath,
            'waktu_rapat'   => $waktuRapat,
        ]);

        if ($request->filled('peserta')) {
            $meeting->pesertaUsers()->sync($request->peserta);
        }

        return redirect()->back()->with('success', 'Laporan meeting berhasil disimpan!');
    }

    /**
     * Convert nama bulan Indonesia ke Inggris agar Carbon bisa parse
     */
    private function convertIndonesianMonthToEnglish($dateString)
    {
        $map = [
            'Januari'   => 'January',
            'Februari'  => 'February',
            'Maret'     => 'March',
            'April'     => 'April',
            'Mei'       => 'May',
            'Juni'      => 'June',
            'Juli'      => 'July',
            'Agustus'   => 'August',
            'September' => 'September',
            'Oktober'   => 'October',
            'November'  => 'November',
            'Desember'  => 'December',
        ];

        return str_replace(array_keys($map), array_values($map), $dateString);
    }

    public function indexKembalikanIniKloBawahGagal(Request $request)
    {
        $query = MeetingReport::query();

        // Filter tanggal
        if ($request->filled(['start_date', 'end_date'])) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('waktu_rapat', [$start, $end]);
        }

        // Filter divisi
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        // Filter sub divisi
        if ($request->filled('sub_divisi')) {
            $query->where('sub_divisi', $request->sub_divisi);
        }

        $reports = $query->orderBy('waktu_rapat', 'desc')->get();

        return view('meeting.index', compact('reports'));
    }

    public function index(Request $request)
    {
        $query = MeetingReport::query();

        // Filter tanggal
        if ($request->filled(['start_date', 'end_date'])) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('waktu_rapat', [$start, $end]);
        }

        // Filter divisi (foreign key id)
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        // Filter sub divisi (foreign key id)
        if ($request->filled('sub_divisi')) {
            $query->where('sub_divisi', $request->sub_divisi);
        }

        // ambil data meeting + relasi
        $reports = $query->with(['divisiRelasi', 'subDivisiRelasi', 'pesertaUsers'])
            ->orderBy('waktu_rapat', 'desc')
            ->get();

        // ambil data dropdown divisi & sub divisi
        $divisis = \App\Models\Divisi::all();
        $subDivisis = \App\Models\SubDivisi::all();

        return view('meeting.index', compact('reports', 'divisis', 'subDivisis'));
    }

    public function export(Request $request)
    {
        $query = MeetingReport::query();

        if ($request->filled('start_date')) {
            $query->whereDate('waktu_rapat', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('waktu_rapat', '<=', $request->end_date);
        }
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }
        if ($request->filled('sub_divisi')) {
            $query->where('sub_divisi', $request->sub_divisi);
        }

        // penting: eager load relasi
        $reports = $query->with(['divisiRelasi', 'subDivisiRelasi', 'pesertaUsers'])
            ->latest()
            ->get();

        $filename = "meeting_reports.csv";
        $handle = fopen('php://temp', 'w+');

        // Header CSV
        fputcsv($handle, ['ID', 'Divisi', 'Sub Divisi', 'Peserta', 'Notulen', 'Waktu Rapat']);

        foreach ($reports as $report) {
            // bikin list peserta bernomor
            $pesertaList = $report->pesertaUsers->map(function ($user, $index) {
                return ($index + 1) . ". " . $user->name;
            })->implode("\n"); // line break supaya jadi list di 1 cell

            fputcsv($handle, [
                $report->id,
                optional($report->divisiRelasi)->nama,
                optional($report->subDivisiRelasi)->nama,
                $pesertaList,
                implode("\n", $report->notulen ?? []), // notulen juga bisa dipisah per baris
                $report->waktu_rapat,
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return \Response::make($content, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename"
        ]);
    }

    public function edit($id)
    {
        $report = MeetingReport::with('pesertaUsers')->findOrFail($id);
        return view('meeting.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'notulen'       => 'required|string', // textarea kirim string
            'divisi'        => 'required|string',
            'sub_divisi'    => 'nullable|string',
            'peserta'       => 'nullable|array',
            'waktu_rapat'   => 'required|string',
            'capture_image' => 'nullable|string',
        ]);

        $report = MeetingReport::findOrFail($id);

        $photoPath = $report->capture_image;

        // handle upload/update foto
        if ($request->filled('capture_image')) {
            try {
                $dataUrl = $request->capture_image;
                if (str_contains($dataUrl, ',')) {
                    [, $base64] = explode(',', $dataUrl, 2);
                } else {
                    $base64 = $dataUrl;
                }
                $binary = base64_decode($base64);

                $image = \Image::make($binary)
                    ->orientate()
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('jpg', 80);

                $filename = 'meeting_' . now()->format('Ymd_His') . '_' . \Str::random(6) . '.jpg';
                $dir = 'meeting_photos';

                \Storage::disk('public')->put($dir . '/' . $filename, (string) $image);
                $photoPath = $dir . '/' . $filename;
            } catch (\Throwable $e) {
                // kalau gagal proses gambar, biarin aja tetap pakai $photoPath lama
            }
        }

        // format waktu rapat
        $waktuString = preg_replace('/^[A-Za-zÀ-ÿ]+,\s*/u', '', $request->waktu_rapat);
        $waktuString = $this->convertIndonesianMonthToEnglish($waktuString);

        $waktuRapat = \Carbon\Carbon::createFromFormat('d F Y - H:i:s', $waktuString, 'Asia/Jakarta')
            ->format('Y-m-d H:i:s');

        // convert notulen string → array (per baris)
        $notulenArray = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $request->notulen)));

        $report->update([
            'notulen'       => $notulenArray,
            'divisi'        => $request->divisi,
            'sub_divisi'    => $request->sub_divisi,
            'capture_image' => $photoPath,
            'waktu_rapat'   => $waktuRapat,
        ]);

        // update peserta rapat
        $report->pesertaUsers()->sync($request->input('peserta', []));

        return redirect()->route('meeting.index')->with('success', 'Laporan meeting berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $report = MeetingReport::findOrFail($id);

        if ($report->capture_image && \Storage::disk('public')->exists($report->capture_image)) {
            \Storage::disk('public')->delete($report->capture_image);
        }

        $report->pesertaUsers()->detach();
        $report->delete();

        return redirect()->route('meeting.index')->with('success', 'Laporan meeting berhasil dihapus!');
    }
}
