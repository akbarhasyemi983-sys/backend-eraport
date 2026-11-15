<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        return response()->json(
            MataPelajaran::with('tujuanPembelajaran')->orderBy('nama')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'  => 'required|string|unique:mata_pelajaran',
            'nama'  => 'required|string',
            'fase'  => 'sometimes|string',
            'kelas' => 'sometimes|string',
        ]);

        return response()->json(MataPelajaran::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(
            MataPelajaran::with('tujuanPembelajaran')->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->update($request->all());
        return response()->json($mapel);
    }

    public function destroy($id)
    {
        MataPelajaran::findOrFail($id)->delete();
        return response()->json(['message' => 'Mapel berhasil dihapus']);
    }
}
