<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\AudioController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\PlaylistController;


    //login
    Route::post('login', [LoginController::class, 'login']);

    // Géneros
    Route::get('/genres', [GenreController::class, 'index']);
    Route::post('/genres', [GenreController::class, 'store']);
    Route::get('/genres/{id}', [GenreController::class, 'show']);
    Route::put('/genres/{id}', [GenreController::class, 'update']);
    Route::delete('/genres/{id}', [GenreController::class, 'destroy']);

    // Álbumes
    Route::get('/albums', [AlbumController::class, 'index']);
    Route::post('/albums', [AlbumController::class, 'store']);
    Route::get('/albums/{id}', [AlbumController::class, 'show']);
    Route::put('/albums/{id}', [AlbumController::class, 'update']);
    Route::delete('/albums/{id}', [AlbumController::class, 'destroy']);

    // Audios
    Route::get('/audios', [AudioController::class, 'index']);
    Route::post('/audios', [AudioController::class, 'store']);
    Route::get('/audios/{id}', [AudioController::class, 'show']);
    Route::put('/audios/{id}', [AudioController::class, 'update']);
    Route::delete('/audios/{id}', [AudioController::class, 'destroy']);

    // Podcasts
    Route::get('podcasts', [PodcastController::class, 'index']);
    Route::post('podcasts', [PodcastController::class, 'store']);
    Route::get('podcasts/{id}', [PodcastController::class, 'show']);
    Route::put('podcasts/{id}', [PodcastController::class, 'update']);
    Route::delete('podcasts/{id}', [PodcastController::class, 'destroy']);

    // Rutas para Playlist
    Route::apiResource('playlists', PlaylistController::class);
        // Audios
        Route::post('/playlists/{playlist}/audios', [PlaylistController::class, 'addAudio']);
        Route::get('/playlists/{playlist}/audios', [PlaylistController::class, 'listAudios']);
        Route::put('/playlists/{playlist}/audios/{audio}', [PlaylistController::class, 'updateAudioOrder']);
        Route::delete('/playlists/{playlist}/audios/{audio}', [PlaylistController::class, 'removeAudio']);

        // Podcasts
        Route::post('/playlists/{playlist}/podcasts', [PlaylistController::class, 'addPodcast']);
        Route::get('/playlists/{playlist}/podcasts', [PlaylistController::class, 'listPodcasts']);
        Route::put('/playlists/{playlist}/podcasts/{podcast}', [PlaylistController::class, 'updatePodcastOrder']);
        Route::delete('/playlists/{playlist}/podcasts/{podcast}', [PlaylistController::class, 'removePodcast']);


    Route::prefix('likes')->group(function () {
        Route::post('/toggle/{audioId}', [LikeController::class, 'toggleLike']);
        Route::get('/check/{audioId}', [LikeController::class, 'checkLike']);
        Route::get('/user', [LikeController::class, 'getUserLikes']);
    });



    // use App\Http\Controllers\Api\TagController;
    // use App\Http\Controllers\Api\LikeController;
    // use App\Http\Controllers\Api\HistoryController;


// Rutas para Auth
// Route::post('register', [RegisterController::class, 'register']);
// Route::post('forgot-password', [LoginController::class, 'forgotPassword']);
// Route::post('reset-password', [LoginController::class, 'resetPassword'])->name('password.reset');


// // Rutas para Tag
// Route::apiResource('tags', TagController::class);
// // Rutas para Like
// Route::apiResource('likes', LikeController::class);
// // Rutas para History
// Route::apiResource('histories', HistoryController::class);
// // Rutas para asociar y desasociar tags con audios y podcasts
// Route::post('/audios/{audio}/tags', [TagController::class, 'attachTagToAudio']);
// Route::delete('/audios/{audio}/tags', [TagController::class, 'detachTagFromAudio']);
// Route::post('/podcasts/{podcast}/tags', [TagController::class, 'attachTagToPodcast']);
// Route::delete('/podcasts/{podcast}/tags', [TagController::class, 'detachTagFromPodcast']);
