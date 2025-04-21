<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory, ApiTrait;

    protected $table = 'playlists';
    protected $guarded = [];

    // Listas Blancas para incluir relaciones, filtrar y ordenar

    protected $allowIncluded = ['user', 'audios', 'podcasts']; // Relaciona con User, Audio, Podcast
    protected $allowFilter = ['id', 'name', 'description', 'user_id']; // Filtros posibles
    protected $allowSort = ['id', 'name', 'created_at']; // Campos de ordenamiento




    // Relación de uno a muchos
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Relación de muchos a muchos
    public function audios()
    {
        return $this->belongsToMany(Audio::class, 'audio_playlist');
    }

    // Relación de muchos a muchos
    public function podcasts()
    {
        return $this->belongsToMany(Podcast::class, 'playlist_podcast');
    }

}
