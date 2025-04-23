<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($audioId)
    {
        try {
            $audio = Audio::findOrFail($audioId);
            $like = Like::where('audio_id', $audioId)->first();

            if ($like) {
                $like->delete();
                $liked = false;
                $message = 'Like eliminado';
            } else {
                Like::create(['audio_id' => $audioId]);
                $liked = true;
                $message = 'Like agregado';
            }

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'liked' => $liked,
                'likes_count' => $audio->likes()->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar el like: ' . $e->getMessage()
            ], 500);
        }
    }

public function checkLike($audioId)
{
    try {
        $liked = Like::where('audio_id', $audioId)->exists();

        return response()->json([
            'status' => 'success',
            'liked' => $liked
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al verificar el like'
        ], 500);
    }
}
    public function getUserLikes()
    {
        try {
            $user = Auth::user();
            $likes = $user->likes()->with('audio')->get();

            return response()->json([
                'status' => 'success',
                'data' => $likes,
                'message' => 'Likes obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los likes'
            ], 500);
        }
    }

    public function getAllLikedAudios()
    {
        try {
            $audios = Audio::whereHas('likes')
                ->withCount('likes')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $audios,
                'message' => 'Audios con likes obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los audios con likes'
            ], 500);
        }
    }
}
