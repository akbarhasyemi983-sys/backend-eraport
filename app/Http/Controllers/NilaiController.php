<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Nilai::with(['siswa', 'mapel']);

        if ($request->has('siswa_id'))  $query->where('siswa_id', $request->siswa_id);
        if ($request->has('mapel_id'))  $query->where('mapel_id', $request->mapel_id);
        if ($request->has('tahun_ajaran')) $query->where('tahun_ajaran', $request->tahun_ajaran);
        if ($request->has('semester'))  $query->where('semester', $request->semester);

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id'     => 'required|exists:siswa,id',
            'mapel_id'     => 'required|exists:mata_pelajaran,id',
            'nilai_tugas'  => 'required|numeric|min:0|max:100',
            'nilai_uh'     => 'required|numeric|min:0|max:100',
            'nilai_uts'    => 'required|numeric|min:0|max:100',
            'nilai_uas'    => 'required|numeric|min:0|max:100',
            'tahun_ajaran' => 'sometimes|string',
            'semester'     => 'sometimes|string',
        ]);

        // Hitung nilai akhir: Tugas 20% + UH 30% + UTS 20% + UAS 30%
        $nilaiAkhir = ($validated['nilai_tugas'] * 0.20)
                    + ($validated['nilai_uh']    * 0.30)
                    + ($validated['nilai_uts']   * 0.20)
                    + ($validated['nilai_uas']   * 0.30);

        $validated['nilai_akhir'] = round($nilaiAkhir, 2);
        $validated['predikat']    = $this->getPredikat($nilaiAkhir);

        // Upsert — update jika sudah ada
        $nilai = Nilai::updateOrCreate(
            [
                'siswa_id'     => $validated['siswa_id'],
                'mapel_id'     => $validated['mapel_id'],
                'tahun_ajaran' => $validated['tahun_ajaran'] ?? '2025/2026',
                'semester'     => $validated['semester'] ?? 'Ganjil',
            ],
            $validated
        );

        return response()->json($nilai->load(['siswa', 'mapel']), 201);
    }

    public function update(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);
        $data  = $request->all();

        // Recalculate jika ada perubahan nilai
        $tugas = $data['nilai_tugas'] ?? $nilai->nilai_tugas;
        $uh    = $data['nilai_uh']    ?? $nilai->nilai_uh;
        $uts   = $data['nilai_uts']   ?? $nilai->nilai_uts;
        $uas   = $data['nilai_uas']   ?? $nilai->nilai_uas;

        $nilaiAkhir = ($tugas * 0.20) + ($uh * 0.30) + ($uts * 0.20) + ($uas * 0.30);
        $data['nilai_akhir'] = round($nilaiAkhir, 2);
        $data['predikat']    = $this->getPredikat($nilaiAkhir);

        $nilai->update($data);
        return response()->json($nilai->load(['siswa', 'mapel']));
    }

    private function getPredikat(float $nilai): string
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        return 'D';
    }
}
