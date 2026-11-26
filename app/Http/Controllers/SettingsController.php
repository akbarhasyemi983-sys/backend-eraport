<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    // GET /settings — return all settings as key-value object
    public function index()
    {
        $rows = DB::table('settings')->get();
        $data = [];
        foreach ($rows as $r) {
            $data[$r->key] = $r->value;
        }
        return response()->json(['data' => $data]);
    }

    // PUT /settings — update multiple keys at once
    public function update(Request $request)
    {
        $allowed = ['school_name','school_address','tahun_ajaran','semester','kepala_sekolah','nip_kepsek'];
        $updated = [];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $request->input($key), 'updated_at' => now()]
                );
                $updated[$key] = $request->input($key);
            }
        }

        return response()->json(['message' => 'Settings updated', 'data' => $updated]);
    }

    // GET a single setting by key
    public function show($key)
    {
        $row = DB::table('settings')->where('key', $key)->first();
        if (!$row) {
            return response()->json(['message' => 'Key not found'], 404);
        }
        return response()->json(['key' => $row->key, 'value' => $row->value]);
    }
}
