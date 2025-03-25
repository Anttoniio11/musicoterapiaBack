<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Podcast;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
        return response()->json($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:tags,name',
        ]);

        $tag = Tag::create($validated);

        return response()->json($tag, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $tag = Tag::included()->findOrFail($id);
        return response()->json($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:tags,name',
        ]);

        $tag->update($validated);
        return response()->json($tag);
   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(null, 204);
    }

    // Asociar un tag a un audio
    public function attachTagToAudio(Request $request, $audioId)
    {
        $audio = Audio::findOrFail($audioId);
        $tag = Tag::findOrFail($request->input('tag_id'));
        $audio->tags()->attach($tag->id);

        return response()->json(['message' => 'Tag attached to audio successfully']);
    }

    // Desasociar un tag de un audio
    public function detachTagFromAudio(Request $request, $audioId)
    {
        $audio = Audio::findOrFail($audioId);
        $tag = Tag::findOrFail($request->input('tag_id'));
        $audio->tags()->detach($tag->id);

        return response()->json(['message' => 'Tag detached from audio successfully']);
    }

    // Asociar un tag a un podcast
    public function attachTagToPodcast(Request $request, $podcastId)
    {
        $podcast = Podcast::findOrFail($podcastId);
        $tag = Tag::findOrFail($request->input('tag_id'));
        $podcast->tags()->attach($tag->id);

        return response()->json(['message' => 'Tag attached to podcast successfully']);
    }

    // Desasociar un tag de un podcast
    public function detachTagFromPodcast(Request $request, $podcastId)
    {
        $podcast = Podcast::findOrFail($podcastId);
        $tag = Tag::findOrFail($request->input('tag_id'));
        $podcast->tags()->detach($tag->id);

        return response()->json(['message' => 'Tag detached from podcast successfully']);
    }
}
