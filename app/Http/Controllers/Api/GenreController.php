<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        try {
            $genres = Genre::all();
            return response()->json([
                'status' => 'success',
                'data' => $genres,
                'message' => 'Géneros obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching genres: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al obtener géneros'], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            $publicId = null;

            if ($request->hasFile('image_file')) {
                $upload = Cloudinary::upload($request->file('image_file')->getRealPath(), [
                    'folder' => 'genres',
                    'public_id' => Str::slug($request->name) . '_' . time()
                ]);
                $imagePath = $upload->getSecurePath();
                $publicId = $upload->getPublicId();
            }

            $genre = Genre::create([
                'name' => $request->name,
                'description' => $request->description,
                'image_path' => $imagePath,
                'cloudinary_public_id' => $publicId
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $genre,
                'message' => 'Género creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating genre: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al crear género'], 500);
        }
    }

    public function show($id)
    {
        try {
            $genre = Genre::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $genre,
                'message' => 'Género obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Género no encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255|unique:genres,name,' . $id,
            'description' => 'sometimes|nullable|string',
            'image_file' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image_file')) {
                if ($genre->cloudinary_public_id) {
                    Cloudinary::destroy($genre->cloudinary_public_id);
                }

                $upload = Cloudinary::upload($request->file('image_file')->getRealPath(), [
                    'folder' => 'genres',
                    'public_id' => Str::slug($request->name) . '_' . time()
                ]);

                $genre->image_path = $upload->getSecurePath();
                $genre->cloudinary_public_id = $upload->getPublicId();
            }

            $genre->update($request->only(['name', 'description']));

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $genre,
                'message' => 'Género actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating genre: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al actualizar género'], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $genre = Genre::findOrFail($id);

            if ($genre->cloudinary_public_id) {
                Cloudinary::destroy($genre->cloudinary_public_id);
            }

            $genre->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Género eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting genre: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar género'], 500);
        }
    }
}
