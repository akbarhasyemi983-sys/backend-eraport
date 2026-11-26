<?php

namespace App\Http\Controllers;

use App\Models\TujuanPembelajaran;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class TujuanPembelajaranController extends Controller
{
    // GET /api/mapel/{mapelId}/tp
    public function index($mapelId)
    {
        MataPelajaran::findOrFail($mapelId); // 404 jika mapel tidak ada
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->get();
        return response()->json($tp);
    }

    // GET /api/mapel/{mapelId}/tp/{id}
    public function show($mapelId, $id)
    {
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->findOrFail($id);
        return response()->json($tp);
    }

    // POST /api/mapel/{mapelId}/tp
    public function store(Request $request, $mapelId)
    {
        MataPelajaran::findOrFail($mapelId);

        $validated = $request->validate([
            'kode'      => 'required|string|max:20',
            'deskripsi' => 'required|string',
        ]);

        $validated['mapel_id'] = $mapelId;

        return response()->json(TujuanPembelajaran::create($validated), 201);
    }

    // PUT /api/mapel/{mapelId}/tp/{id}
    public function update(Request $request, $mapelId, $id)
    {
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->findOrFail($id);
        $tp->update($request->only(['kode', 'deskripsi']));
        return response()->json($tp);
    }

    // DELETE /api/mapel/{mapelId}/tp/{id}
    public function destroy($mapelId, $id)
    {
        $tp = TujuanPembelajaran::where('mapel_id', $mapelId)->findOrFail($id);
        $tp->delete();
        return response()->json(['message' => 'TP berhasil dihapus']);
    }
}
