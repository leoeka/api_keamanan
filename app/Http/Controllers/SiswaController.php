<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Mendapatkan semua data siswa.
     */
    public function index()
    {
        try {
            $siswa = Siswa::all();
            return response()->json($siswa, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data siswa.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * (Tidak digunakan dalam API berbasis JSON)
     */
    public function create()
    {
        // Tidak diperlukan untuk API JSON
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data siswa baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'kelas' => 'required|string|regex:/^X{0,3}I{0,3} (IPA|IPS) [1-9]$/u',
            'umur' => 'required|integer|between:6,18',
        ]);

        try {
            $siswa = Siswa::create($validatedData);
            return response()->json($siswa, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan data siswa.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * Menampilkan data siswa berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            return response()->json($siswa, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data siswa.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * (Tidak digunakan dalam API berbasis JSON)
     */
    public function edit(string $id)
    {
        // Tidak diperlukan untuk API JSON
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data siswa.
     */
    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);

            $validatedData = $request->validate([
                'nama' => 'sometimes|required|regex:/^[a-zA-Z\s]+$/|max:255',
                'kelas' => 'sometimes|required|regex:/^X{0,3}I{0,3} (IPA|IPS) [1-9]$/',
                'umur' => 'sometimes|required|integer|between:6,18',
            ]);

            $siswa->update($validatedData);

            return response()->json($siswa, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validasi input gagal.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui data siswa.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus data siswa.
     */
    public function destroy(string $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();

            return response()->json(['message' => 'Data siswa berhasil dihapus.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data siswa.'], 500);
        }
    }
}
