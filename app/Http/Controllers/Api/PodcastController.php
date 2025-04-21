<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PodcastController extends Controller
{
    public function index()
    {
        try {
            $podcasts = Podcast::all();
            return response()->json([
                'status' => 'success',
                'data' => $podcasts,
                'message' => 'Podcasts obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching podcasts: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener podcasts'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_file' => 'required|mimes:mp4,mov,ogg,qt|max:20480',
            'duration' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $imageData = [];
            $videoData = [];

            // Subir imagen
            if ($request->hasFile('image_file')) {
                $upload = Cloudinary::upload($request->file('image_file')->getRealPath(), [
                    'folder' => 'podcasts/images',
                    'public_id' => Str::slug($request->title) . '_' . time()
                ]);
                $imageData = [
                    'image_file' => $upload->getSecurePath(),
                    'image_public_id' => $upload->getPublicId()
                ];
            }

            // Subir video
            $videoUpload = Cloudinary::upload($request->file('video_file')->getRealPath(), [
                'resource_type' => 'video',
                'folder' => 'podcasts/videos',
                'public_id' => Str::slug($request->title) . '_' . time()
            ]);
            $videoData = [
                'video_file' => $videoUpload->getSecurePath(),
                'video_public_id' => $videoUpload->getPublicId()
            ];

            $podcast = Podcast::create(array_merge(
                $request->only(['title', 'description', 'duration']),
                $imageData,
                $videoData
            ));

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $podcast,
                'message' => 'Podcast creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating podcast: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear podcast'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $podcast = Podcast::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $podcast,
                'message' => 'Podcast obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Podcast no encontrado'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $podcast = Podcast::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'image_file' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_file' => 'sometimes|nullable|mimes:mp4,mov,ogg,qt|max:20480',
            'duration' => 'sometimes|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Actualizar imagen
            if ($request->hasFile('image_file')) {
                if ($podcast->image_public_id) {
                    Cloudinary::destroy($podcast->image_public_id);
                }

                $upload = Cloudinary::upload($request->file('image_file')->getRealPath(), [
                    'folder' => 'podcasts/images',
                    'public_id' => Str::slug($request->title) . '_' . time()
                ]);

                $podcast->image_file = $upload->getSecurePath();
                $podcast->image_public_id = $upload->getPublicId();
            }

            // Actualizar video
            if ($request->hasFile('video_file')) {
                if ($podcast->video_public_id) {
                    Cloudinary::destroy($podcast->video_public_id, ['resource_type' => 'video']);
                }

                $upload = Cloudinary::upload($request->file('video_file')->getRealPath(), [
                    'resource_type' => 'video',
                    'folder' => 'podcasts/videos',
                    'public_id' => Str::slug($request->title) . '_' . time()
                ]);

                $podcast->video_file = $upload->getSecurePath();
                $podcast->video_public_id = $upload->getPublicId();
            }

            $podcast->update($request->only(['title', 'description', 'duration']));

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $podcast,
                'message' => 'Podcast actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating podcast: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar podcast'
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $podcast = Podcast::findOrFail($id);

            if ($podcast->image_public_id) {
                Cloudinary::destroy($podcast->image_public_id);
            }

            if ($podcast->video_public_id) {
                Cloudinary::destroy($podcast->video_public_id, ['resource_type' => 'video']);
            }

            $podcast->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Podcast eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting podcast: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar podcast'
            ], 500);
        }
    }
}
