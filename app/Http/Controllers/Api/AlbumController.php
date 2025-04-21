<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public function index()
    {
        try {
            $albums = Album::with('audios')->get();
            return response()->json([
                'status' => 'success',
                'data' => $albums,
                'message' => 'Álbumes obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al obtener álbumes'], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $upload = Cloudinary::upload($request->file('image_file')->getRealPath(), [
                'folder' => 'albums',
                'public_id' => Str::slug($request->title) . '_' . time()
            ]);

            $album = Album::create([
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $upload->getSecurePath(),
                'cloudinary_public_id' => $upload->getPublicId()
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $album,
                'message' => 'Álbum creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Error al crear álbum: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $album = Album::with('audios')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $album,
                'message' => 'Álbum obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Álbum no encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $album = Album::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'image_file' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image_file')) {
                Cloudinary::destroy($album->cloudinary_public_id);

                $upload = Cloudinary::upload($request->file('image_file')->getRealPath(), [
                    'folder' => 'albums',
                    'public_id' => Str::slug($request->title) . '_' . time()
                ]);

                $album->image_path = $upload->getSecurePath();
                $album->cloudinary_public_id = $upload->getPublicId();
            }

            $album->update($request->only(['title', 'description']));

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $album,
                'message' => 'Álbum actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Error al actualizar álbum'], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $album = Album::findOrFail($id);

            if ($album->cloudinary_public_id) {
                Cloudinary::destroy($album->cloudinary_public_id);
            }

            $album->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Álbum eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar álbum'], 500);
        }
    }
}
