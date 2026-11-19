<?php

namespace App\Http\Controllers;

use App\Models\AturanPredikat;
use Illuminate\Http\Request;

class AturanPredikatController extends Controller
{
    public function index()
    {
        $aturan = AturanPredikat::orderBy('nilai_min')->get();
        return response()->json($aturan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'predikat'  => 'required|string|max:5',
            'nilai_min' => 'required|numeric|min:0|max:100',
            'nilai_max' => 'required|numeric|min:0|max:100|gte:nilai_min',
            'deskripsi' => 'nullable|string|max:200',
        ]);

        // Cek overlap dengan aturan yang sudah ada
        $overlap = AturanPredikat::where(function ($q) use ($request) {
            $q->whereBetween('nilai_min', [$request->nilai_min, $request->nilai_max])
              ->orWhereBetween('nilai_max', [$request->nilai_min, $request->nilai_max])
              ->orWhere(function ($q2) use ($request) {
                  $q2->where('nilai_min', '<=', $request->nilai_min)
                     ->where('nilai_max', '>=', $request->nilai_max);
              });
        })->exists();

        if ($overlap) {
            return response()->json(['message' => 'Rentang nilai bertumpang tindih dengan aturan yang sudah ada'], 422);
        }

        $aturan = AturanPredikat::create($request->only(['predikat', 'nilai_min', 'nilai_max', 'deskripsi']));
        return response()->json($aturan, 201);
    }

    public function show($id)
    {
        return response()->json(AturanPredikat::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'predikat'  => 'sometimes|required|string|max:5',
            'nilai_min' => 'sometimes|required|numeric|min:0|max:100',
            'nilai_max' => 'sometimes|required|numeric|min:0|max:100|gte:nilai_min',
            'deskripsi' => 'nullable|string|max:200',
        ]);

        $aturan = AturanPredikat::findOrFail($id);
        $aturan->update($request->only(['predikat', 'nilai_min', 'nilai_max', 'deskripsi']));
        return response()->json($aturan);
    }

    public function destroy($id)
    {
        AturanPredikat::findOrFail($id)->delete();
        return response()->json(['message' => 'Aturan predikat berhasil dihapus']);
    }

    // Reset ke default (A/B/C/D)
    public function resetDefault()
    {
        AturanPredikat::truncate();

        $defaults = [
            ['predikat' => 'A', 'nilai_min' => 90, 'nilai_max' => 100, 'deskripsi' => 'Sangat Baik'],
            ['predikat' => 'B', 'nilai_min' => 75, 'nilai_max' => 89,  'deskripsi' => 'Baik'],
            ['predikat' => 'C', 'nilai_min' => 60, 'nilai_max' => 74,  'deskripsi' => 'Cukup'],
            ['predikat' => 'D', 'nilai_min' => 0,  'nilai_max' => 59,  'deskripsi' => 'Perlu Bimbingan'],
        ];

        foreach ($defaults as $d) {
            AturanPredikat::create($d);
        }

        return response()->json(['message' => 'Aturan predikat direset ke default', 'data' => AturanPredikat::orderBy('nilai_min', 'desc')->get()]);
    }
}
