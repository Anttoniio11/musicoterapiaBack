<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{

    public function index()
    {
        $playlists = Playlist::included()
            ->filter()
            ->sort()
            ->getOrPaginate();

        return response()->json($playlists);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'audio_id' => 'required|exists:audios,id', // Validamos que el audio exista
        ]);

        // Crear la nueva playlist
        $playlist = Playlist::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
        ]);

        // Asociar el audio que el usuario ha seleccionado con la nueva playlist
        $playlist->audios()->attach($request->audio_id);

        return response()->json($playlist, 201);
    }


    public function show(Playlist $playlist)
    {
        // Cargar las relaciones con audios y podcasts si están presentes
        $playlist->load('audios', 'podcasts');

        return response()->json($playlist);
    }


    public function update(Request $request, Playlist $playlist)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'sometimes|required|exists:users,id',
            'audio_id' => 'sometimes|required|exists:audios,id', // Validar un solo audio_id
        ]);
    
        // Actualizar los detalles de la playlist
        $playlist->update($request->only(['name', 'user_id', 'description']));
    
        // Si se proporciona audio_id, agregarlo a la playlist
        if ($request->has('audio_id')) {
            $playlist->audios()->attach($request->audio_id);
        }
    
        return response()->json($playlist);
       
    }


    public function destroy(Playlist $playlist)
    {
        $playlist->delete();

        return response()->json(null, 204);
    }

    public function removeAudio(Playlist $playlist, $audioId)
{
    // Eliminar la relación entre la playlist y el audio en la tabla pivote
    $playlist->audios()->detach($audioId);

    return response()->json(['message' => 'Audio eliminado de la playlist'], 200);
}

public function removePodcast(Playlist $playlist, $podcastId)
{
    // Eliminar la relación entre la playlist y el podcast en la tabla pivote
    $playlist->podcasts()->detach($podcastId);

    return response()->json(['message' => 'Podcast eliminado de la playlist'], 200);
}

//http://tranquilidad.test/v1/playlists/{playlist_id}/audios/{audio_id}

}
