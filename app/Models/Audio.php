<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory, ApiTrait;

    protected $table = 'audios';
    protected $fillable = [
        'title',
        'description',
        'image_file',
        'image_public_id',
        'audio_file',
        'audio_public_id',
        'duration',
        'genre_id',
        'album_id',
        'es_binaural',
        'frecuencia'
    ];

    // Listas blancas para inclusión, filtrado y ordenamiento en la API
    protected $allowIncluded = ['genre', 'album', 'playlists', 'tags', 'likes', 'histories'];
    protected $allowFilter = ['id', 'title', 'es_binaural', 'duration', 'album_id']; // Se agrega 'album_id' para filtrar por álbum
    protected $allowSort = ['id', 'title'];

    protected $casts = [
        'es_binaural' => 'boolean'
    ];

    // Relación Uno a Muchos Inversa con Genre
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    // Relación Uno a Muchos Inversa con Album
    public function album()
    {
        return $this->belongsTo(Album::class)->withDefault(); // Con `withDefault()` evita errores si no tiene álbum
    }

    // Relación Muchos a Muchos polimórfica con el modelo Tag
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    // Agrega esto al modelo Audio
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Relación Uno a Muchos polimórfica con el modelo History
    public function histories()
    {
        return $this->morphMany(History::class, 'historable');
    }

    // Relación Muchos a Muchos con Playlists
    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'audio_playlist');
    }
}
