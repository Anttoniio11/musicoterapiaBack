<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AudioController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Audio::query()->with(['genre', 'album']);

            // Filtros
            $filters = $request->only(['album_id', 'genre_id', 'es_binaural', 'title']);
            foreach ($filters as $field => $value) {
                if ($value !== null) {
                    if ($field === 'title') {
                        $query->where($field, 'LIKE', "%$value%");
                    } else {
                        $query->where($field, $value);
                    }
                }
            }

            $audios = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $audios,
                'message' => 'Audios obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting audios: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los audios'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'audio_file' => 'required|mimes:mp3,wav,aac,ogg|max:25000',
                'duration' => 'required|integer|min:1',
                'genre_id' => 'required|exists:genres,id',
                'album_id' => 'nullable|exists:albums,id',
                'es_binaural' => 'required|boolean',
                'frecuencia' => 'required_if:es_binaural,true|nullable|numeric|between:0.1,1000',
            ], $this->validationMessages());

            // Subir imagen
            $imageUpload = Cloudinary::upload(
                $request->file('image_file')->getRealPath(),
                ['folder' => 'musicoterapia/images']
            );

            // Subir audio
            $audioUpload = Cloudinary::upload(
                $request->file('audio_file')->getRealPath(),
                [
                    'resource_type' => 'video',
                    'folder' => 'musicoterapia/audios'
                ]
            );

            $audio = Audio::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image_file' => $imageUpload->getSecurePath(),
                'image_public_id' => $imageUpload->getPublicId(),
                'audio_file' => $audioUpload->getSecurePath(),
                'audio_public_id' => $audioUpload->getPublicId(),
                'duration' => $validated['duration'],
                'genre_id' => $validated['genre_id'],
                'album_id' => $validated['album_id'],
                'es_binaural' => $validated['es_binaural'],
                'frecuencia' => $validated['frecuencia'],
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $audio,
                'message' => 'Audio creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating audio: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el audio: ' . $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $audio = Audio::with(['genre', 'album', 'tags', 'likes', 'histories', 'playlists'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $audio,
                'message' => 'Audio obtenido exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting audio: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Audio no encontrado'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $audio = Audio::findOrFail($id);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'image_file' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'audio_file' => 'sometimes|mimes:mp3,wav,aac,ogg|max:25000',
                'duration' => 'sometimes|integer|min:1',
                'genre_id' => 'sometimes|exists:genres,id',
                'album_id' => 'sometimes|nullable|exists:albums,id',
                'es_binaural' => 'sometimes|boolean',
                'frecuencia' => 'required_if:es_binaural,true|nullable|numeric|between:0.1,1000',
            ], $this->validationMessages());

            // Actualizar imagen
            if ($request->hasFile('image_file')) {
                // Eliminar imagen anterior
                if ($audio->image_public_id) {
                    Cloudinary::destroy($audio->image_public_id);
                }

                $imageUpload = Cloudinary::upload(
                    $request->file('image_file')->getRealPath(),
                    ['folder' => 'musicoterapia/images']
                );

                $audio->image_file = $imageUpload->getSecurePath();
                $audio->image_public_id = $imageUpload->getPublicId();
            }

            // Actualizar audio
            if ($request->hasFile('audio_file')) {
                // Eliminar audio anterior
                if ($audio->audio_public_id) {
                    Cloudinary::destroy($audio->audio_public_id, ['resource_type' => 'video']);
                }

                $audioUpload = Cloudinary::upload(
                    $request->file('audio_file')->getRealPath(),
                    [
                        'resource_type' => 'video',
                        'folder' => 'musicoterapia/audios'
                    ]
                );

                $audio->audio_file = $audioUpload->getSecurePath();
                $audio->audio_public_id = $audioUpload->getPublicId();
            }

            // Actualizar otros campos
            $audio->fill($validated);
            $audio->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $audio,
                'message' => 'Audio actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating audio: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el audio: ' . $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $audio = Audio::findOrFail($id);

            // Eliminar recursos de Cloudinary
            if ($audio->image_public_id) {
                Cloudinary::destroy($audio->image_public_id);
            }

            if ($audio->audio_public_id) {
                Cloudinary::destroy($audio->audio_public_id, ['resource_type' => 'video']);
            }

            $audio->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Audio eliminado permanentemente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting audio: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar el audio'
            ], 500);
        }
    }

    private function validationMessages()
    {
        return [
            'title.required' => 'El título es obligatorio',
            'audio_file.required' => 'El archivo de audio es obligatorio',
            'audio_file.mimes' => 'Formatos permitidos: mp3, wav, aac, ogg',
            'audio_file.max' => 'El audio no puede superar los 25MB',
            'image_file.image' => 'El archivo debe ser una imagen válida',
            'frecuencia.required_if' => 'La frecuencia es obligatoria para sonidos binaurales',
            'genre_id.exists' => 'El género seleccionado no existe',
            'duration.min' => 'La duración mínima es 1 segundo',
        ];
    }
}
