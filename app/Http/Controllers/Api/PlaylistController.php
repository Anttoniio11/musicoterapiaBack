<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    public function index()
    {
        $playlists = Playlist::where('user_id', Auth::id())
            ->with(['audios', 'podcasts'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $playlists
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $playlist = Playlist::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $playlist
        ], 201);
    }

    public function show(Playlist $playlist)
    {
        $this->authorize('view', $playlist);

        return response()->json([
            'status' => 'success',
            'data' => $playlist->load(['audios', 'podcasts'])
        ]);
    }

    public function update(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        $playlist->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $playlist
        ]);
    }

    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);

        $playlist->delete();
        return response()->noContent();
    }

    // Audios
    public function addAudio(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);

        $request->validate(['audio_id' => 'required|exists:audios,id']);

        // Obtener el máximo order actual + 1
        $maxOrder = $playlist->audios()->max('order') ?? 0;

        $playlist->audios()->attach($request->audio_id, [
            'order' => $maxOrder + 1
        ]);

        return response()->json(['message' => 'Audio agregado'], 201);
    }

    public function listAudios(Playlist $playlist)
    {
        $this->authorize('view', $playlist);

        return response()->json([
            'data' => $playlist->audios()->orderBy('order')->get()
        ]);
    }

    public function updateAudioOrder(Request $request, Playlist $playlist, $audioId)
    {
        $this->authorize('update', $playlist);

        $request->validate(['order' => 'required|integer']);

        $playlist->audios()->updateExistingPivot($audioId, ['order' => $request->order]);

        return response()->json(['message' => 'Orden actualizado']);
    }

    public function removeAudio(Playlist $playlist, $audioId)
    {
        $this->authorize('update', $playlist);

        $playlist->audios()->detach($audioId);
        return response()->json(['message' => 'Audio eliminado']);
    }

    // Podcasts
    public function addPodcast(Request $request, Playlist $playlist)
    {
        // $this->authorize('update', $playlist);

        $request->validate(['podcast_id' => 'required|exists:podcasts,id']);

        $maxOrder = $playlist->podcasts()->max('order') ?? 0;
        $playlist->podcasts()->attach($request->podcast_id, ['order' => $maxOrder + 1]);

        return response()->json(['message' => 'Podcast agregado'], 201);
    }

    public function listPodcasts(Playlist $playlist)
    {
        // $this->authorize('view', $playlist);

        return response()->json([
            'data' => $playlist->podcasts()->orderBy('order')->get()
        ]);
    }

    public function updatePodcastOrder(Request $request, Playlist $playlist, $podcastId)
    {
        $this->authorize('update', $playlist);

        $request->validate(['order' => 'required|integer']);

        $playlist->podcasts()->updateExistingPivot($podcastId, ['order' => $request->order]);

        return response()->json(['message' => 'Orden actualizado']);
    }

    public function removePodcast(Playlist $playlist, $podcastId)
    {
        $this->authorize('update', $playlist);

        $playlist->podcasts()->detach($podcastId);
        return response()->json(['message' => 'Podcast eliminado']);
    }
}
