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
        $validated = $request->validate([
            'kode'    => 'required|string|max:20',
            'nama'    => 'required|string|max:255',
            'kelas'   => 'nullable|string|max:20',
            'fase'    => 'nullable|string|max:5',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $mapel = MataPelajaran::create($validated);
        return response()->json($mapel, 201);
    }

    public function show($id)
    {
        $mapel = MataPelajaran::with('tujuanPembelajaran')->findOrFail($id);
        return response()->json($mapel);
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $validated = $request->validate([
            'kode'    => 'sometimes|required|string|max:20',
            'nama'    => 'sometimes|required|string|max:255',
            'kelas'   => 'nullable|string|max:20',
            'fase'    => 'nullable|string|max:5',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $mapel->update($validated);
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
        MataPelajaran::findOrFail($mapelId);
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)
            ->orderBy('kode')
            ->get();
        return response()->json($tp);
    }

    public function tpStore(Request $request, $mapelId)
    {
        MataPelajaran::findOrFail($mapelId);

        $validated = $request->validate([
            'kode'      => 'required|string|max:20',
            'deskripsi' => 'required|string',
        ]);

        $validated['mapel_id'] = $mapelId;

        $tp = TujuanPembelajaran::create($validated);
        return response()->json($tp, 201);
    }

    public function tpUpdate(Request $request, $mapelId, $tpId)
    {
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->findOrFail($tpId);

        $validated = $request->validate([
            'kode'      => 'sometimes|required|string|max:20',
            'deskripsi' => 'sometimes|required|string',
        ]);

        $tp->update($validated);
        return response()->json($tp);
    }

    public function tpDestroy($mapelId, $tpId)
    {
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->findOrFail($tpId);
        $tp->delete();
        return response()->json(['message' => 'Tujuan pembelajaran berhasil dihapus']);
    }
}
