<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    // ===== MATA PELAJARAN =====

    public function index()
    {
        $mapel = MataPelajaran::with('tujuanPembelajaran')->get();
        return response()->json($mapel);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel'  => 'required|string|max:100',
            'kelas'       => 'required|string|max:20',
            'guru_id'     => 'nullable|exists:users,id',
        ]);

        $mapel = MataPelajaran::create($request->only(['nama_mapel', 'kelas', 'guru_id']));
        return response()->json($mapel, 201);
    }

    public function show($id)
    {
        $mapel = MataPelajaran::with('tujuanPembelajaran')->findOrFail($id);
        return response()->json($mapel);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel'  => 'sometimes|required|string|max:100',
            'kelas'       => 'sometimes|required|string|max:20',
            'guru_id'     => 'nullable|exists:users,id',
        ]);

        $mapel = MataPelajaran::findOrFail($id);
        $mapel->update($request->only(['nama_mapel', 'kelas', 'guru_id']));
        return response()->json($mapel);
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();
        return response()->json(['message' => 'Mata pelajaran berhasil dihapus']);
    }

    // ===== TUJUAN PEMBELAJARAN =====

    public function tpIndex($mapelId)
    {
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->get();
        return response()->json($tp);
    }

    public function tpStore(Request $request, $mapelId)
    {
        $request->validate([
            'deskripsi' => 'required|string',
            'nomor_tp'  => 'required|integer|min:1',
        ]);

        // Pastikan mapel ada
        MataPelajaran::findOrFail($mapelId);

        $tp = TujuanPembelajaran::create([
            'mapel_id'  => $mapelId,
            'deskripsi' => $request->deskripsi,
            'nomor_tp'  => $request->nomor_tp,
        ]);
        return response()->json($tp, 201);
    }

    public function tpUpdate(Request $request, $mapelId, $tpId)
    {
        $request->validate([
            'deskripsi' => 'sometimes|required|string',
            'nomor_tp'  => 'sometimes|required|integer|min:1',
        ]);

        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->findOrFail($tpId);
        $tp->update($request->only(['deskripsi', 'nomor_tp']));
        return response()->json($tp);
    }

    public function tpDestroy($mapelId, $tpId)
    {
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->findOrFail($tpId);
        $tp->delete();
        return response()->json(['message' => 'Tujuan pembelajaran berhasil dihapus']);
    }
}
