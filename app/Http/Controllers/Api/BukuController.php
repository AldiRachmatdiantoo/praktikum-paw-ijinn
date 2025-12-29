<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bukus = Buku::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Buku',
            'data'    => BukuResource::collection($bukus)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul'   => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'stok'    => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $buku = Buku::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Buku Berhasil Ditambahkan',
            'data'    => new BukuResource($buku)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku Tidak Ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Buku',
            'data'    => new BukuResource($buku)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku Tidak Ditemukan'], 404);
        }

        // Validasi (boleh nullable jika hanya update sebagian)
        $validator = Validator::make($request->all(), [
            'judul'   => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'stok'    => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $buku->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Buku Berhasil Diupdate',
            'data'    => new BukuResource($buku)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku Tidak Ditemukan'], 404);
        }

        $buku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku Berhasil Dihapus',
        ], 200);
    }
}
