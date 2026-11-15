<?php

namespace App\Http\Controllers;

use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;

class TujuanPembelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = TujuanPembelajaran::with('mapel');
        if ($request->has('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        return response()->json(TujuanPembelajaran::with('mapel')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mapel_id'   => 'required|exists:mata_pelajaran,id',
            'kode'       => 'required|string',
            'deskripsi'  => 'required|string',
        ]);
        return response()->json(TujuanPembelajaran::create($validated), 201);
    }

    public function update(Request $request, $id)
    {
        $tp = TujuanPembelajaran::findOrFail($id);
        $tp->update($request->all());
        return response()->json($tp);
    }

    public function destroy($id)
    {
        TujuanPembelajaran::findOrFail($id)->delete();
        return response()->json(['message' => 'TP berhasil dihapus']);
    }
}
