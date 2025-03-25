<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, ApiTrait;

    protected $guarded = [];

    //Listas Blancas
    protected $allowIncluded= ['audios','podcasts'];
    protected $allowFilter= ['id','name',];
    protected $allowSort= ['id','name'];

    //Relación Polimórfica muchos a muchos
    public function audios()
    {
        return $this->morphedByMany(Audio::class, 'taggable');
    }

    public function podcasts()
    {
        return $this->morphedByMany(Podcast::class, 'taggable');
    }






   
}
