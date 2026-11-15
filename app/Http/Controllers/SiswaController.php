<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->has('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('nama', 'like', "%$q%")
                   ->orWhere('nis', 'like', "%$q%");
            });
        }

        if ($request->has('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        return response()->json($query->orderBy('nama')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'          => 'required|string|unique:siswa',
            'nama'         => 'required|string',
            'kelas'        => 'required|string',
            'jenis_kelamin'=> 'required|in:L,P',
            'nama_ortu'    => 'nullable|string',
            'no_hp_ortu'   => 'nullable|string',
        ]);

        $siswa = Siswa::create($validated);
        return response()->json($siswa, 201);
    }

    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        return response()->json($siswa);
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nis'          => 'sometimes|string|unique:siswa,nis,' . $id,
            'nama'         => 'sometimes|string',
            'kelas'        => 'sometimes|string',
            'jenis_kelamin'=> 'sometimes|in:L,P',
            'nama_ortu'    => 'nullable|string',
            'no_hp_ortu'   => 'nullable|string',
        ]);

        $siswa->update($validated);
        return response()->json($siswa);
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return response()->json(['message' => 'Siswa berhasil dihapus']);
    }
}
