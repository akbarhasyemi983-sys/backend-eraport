<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    // GET /api/mapel
    public function index()
    {
        $mapel = MataPelajaran::with('tujuanPembelajaran')->get();
        return response()->json($mapel);
    }

    // POST /api/mapel
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'  => 'required|string|max:20|unique:mata_pelajaran,kode',
            'nama'  => 'required|string|max:255',
            'kelas' => 'nullable|string|max:10',
            'fase'  => 'nullable|string|max:5',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $mapel = MataPelajaran::create($validated);

        return response()->json($mapel, 201);
    }

    // GET /api/mapel/{id}
    public function show($id)
    {
        $mapel = MataPelajaran::with('tujuanPembelajaran')->findOrFail($id);
        return response()->json($mapel);
    }

    // PUT /api/mapel/{id}
    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $validated = $request->validate([
            'kode'  => 'sometimes|required|string|max:20|unique:mata_pelajaran,kode,' . $id,
            'nama'  => 'sometimes|required|string|max:255',
            'kelas' => 'nullable|string|max:10',
            'fase'  => 'nullable|string|max:5',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $mapel->update($validated);

        return response()->json($mapel);
    }

    // DELETE /api/mapel/{id}
    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();
        return response()->json(['message' => 'Mata pelajaran berhasil dihapus']);
    }

    // =================== TUJUAN PEMBELAJARAN ===================

    // GET /api/mapel/{mapelId}/tp
    public function indexTP($mapelId)
    {
        $mapel = MataPelajaran::findOrFail($mapelId);
        $tp = $mapel->tujuanPembelajaran()->orderBy('kode')->get();
        return response()->json($tp);
    }

    // POST /api/mapel/{mapelId}/tp
    public function storeTP(Request $request, $mapelId)
    {
        $mapel = MataPelajaran::findOrFail($mapelId);

        $validated = $request->validate([
            'kode'      => 'required|string|max:20',
            'deskripsi' => 'required|string',
        ]);

        $validated['mata_pelajaran_id'] = $mapel->id;

        $tp = TujuanPembelajaran::create($validated);

        return response()->json($tp, 201);
    }

    // PUT /api/mapel/{mapelId}/tp/{tpId}
    public function updateTP(Request $request, $mapelId, $tpId)
    {
        $tp = TujuanPembelajaran::where('mata_pelajaran_id', $mapelId)->findOrFail($tpId);

        $validated = $request->validate([
            'kode'      => 'sometimes|required|string|max:20',
            'deskripsi' => 'sometimes|required|string',
        ]);

        $tp->update($validated);

        return response()->json($tp);
    }

    // DELETE /api/mapel/{mapelId}/tp/{tpId}
    public function destroyTP($mapelId, $tpId)
    {
        $tp = TujuanPembelajaran::where('mata_pelajaran_id', $mapelId)->findOrFail($tpId);
        $tp->delete();
        return response()->json(['message' => 'Tujuan pembelajaran berhasil dihapus']);
    }
}
