<?php

namespace App\Http\Controllers;

use App\Models\AturanPredikat;
use Illuminate\Http\Request;

class AturanPredikatController extends Controller
{
    public function index()
    {
        return response()->json(AturanPredikat::orderBy('mapel')->orderBy('predikat')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mapel'           => 'required|string',
            'tp_kode'         => 'nullable|string',
            'predikat'        => 'required|in:A,B,C,D',
            'narasi_template' => 'required|string',
        ]);
        return response()->json(AturanPredikat::create($validated), 201);
    }

    public function update(Request $request, $id)
    {
        $aturan = AturanPredikat::findOrFail($id);
        $aturan->update($request->all());
        return response()->json($aturan);
    }

    public function destroy($id)
    {
        AturanPredikat::findOrFail($id)->delete();
        return response()->json(['message' => 'Aturan berhasil dihapus']);
    }
}
