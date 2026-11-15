<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\NarasiCapaian;
use App\Models\AturanPredikat;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class NarasiController extends Controller
{
    public function index(Request $request)
    {
        $query = NarasiCapaian::with(['siswa', 'mapel']);

        if ($request->has('siswa_id'))    $query->where('siswa_id', $request->siswa_id);
        if ($request->has('mapel_id'))    $query->where('mapel_id', $request->mapel_id);
        if ($request->has('is_approved')) $query->where('is_approved', $request->boolean('is_approved'));

        return response()->json($query->get());
    }

    public function generate(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
        ]);

        $siswaId  = $request->siswa_id;
        $mapelId  = $request->mapel_id;
        $tahun    = $request->tahun_ajaran ?? '2025/2026';
        $semester = $request->semester ?? 'Ganjil';

        // Ambil nilai siswa untuk mapel ini
        $nilai = Nilai::where('siswa_id', $siswaId)
                      ->where('mapel_id', $mapelId)
                      ->where('tahun_ajaran', $tahun)
                      ->where('semester', $semester)
                      ->first();

        if (!$nilai) {
            return response()->json(['message' => 'Nilai belum diinput untuk siswa ini'], 422);
        }

        $mapel    = MataPelajaran::findOrFail($mapelId);
        $predikat = $nilai->predikat;

        // Cari aturan yang cocok (Rule-Based)
        $aturan = AturanPredikat::where('mapel', $mapel->kode)
                                ->where('predikat', $predikat)
                                ->first();

        if ($aturan) {
            $narasi = $aturan->narasi_template;
        } else {
            // Default narasi jika aturan tidak ditemukan
            $narasi = $this->defaultNarasi($mapel->nama, $predikat, $nilai->nilai_akhir);
        }

        // Simpan atau update narasi
        $narasiRecord = NarasiCapaian::updateOrCreate(
            [
                'siswa_id'     => $siswaId,
                'mapel_id'     => $mapelId,
                'tahun_ajaran' => $tahun,
                'semester'     => $semester,
            ],
            [
                'narasi'       => $narasi,
                'is_generated' => true,
                'is_approved'  => false,
            ]
        );

        return response()->json($narasiRecord->load(['siswa', 'mapel']), 201);
    }

    public function update(Request $request, $id)
    {
        $narasi = NarasiCapaian::findOrFail($id);
        $narasi->update([
            'narasi'       => $request->narasi,
            'is_generated' => false, // sudah diedit manual
        ]);
        return response()->json($narasi->load(['siswa', 'mapel']));
    }

    public function approve($id)
    {
        $narasi = NarasiCapaian::findOrFail($id);
        $narasi->update(['is_approved' => true]);
        return response()->json($narasi->load(['siswa', 'mapel']));
    }

    private function defaultNarasi(string $mapel, string $predikat, float $nilai): string
    {
        $keteranganPredikat = match ($predikat) {
            'A' => 'sangat baik',
            'B' => 'baik',
            'C' => 'cukup',
            default => 'perlu bimbingan',
        };

        $templates = [
            'A' => "Peserta didik menunjukkan pemahaman yang sangat baik dalam mata pelajaran {$mapel}. "
                 . "Peserta didik mampu memahami dan menerapkan konsep-konsep dengan sangat baik "
                 . "dan perlu mempertahankan prestasi yang telah dicapai.",
            'B' => "Peserta didik menunjukkan pemahaman yang baik dalam mata pelajaran {$mapel}. "
                 . "Peserta didik mampu memahami sebagian besar konsep dan perlu terus berlatih "
                 . "untuk meningkatkan kemampuan.",
            'C' => "Peserta didik menunjukkan pemahaman yang cukup dalam mata pelajaran {$mapel}. "
                 . "Peserta didik perlu lebih banyak berlatih dan mendapat bimbingan agar dapat "
                 . "memahami konsep-konsep dengan lebih baik.",
            'D' => "Peserta didik masih memerlukan bimbingan lebih lanjut dalam mata pelajaran {$mapel}. "
                 . "Disarankan agar peserta didik mendapatkan perhatian dan bantuan ekstra dari guru "
                 . "dan orang tua untuk meningkatkan pemahamannya.",
        ];

        return $templates[$predikat] ?? $templates['C'];
    }
}
