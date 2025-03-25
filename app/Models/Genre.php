<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Genre extends Model
{
    use HasFactory, ApiTrait;
    protected $guarded = [];

    //Listas Blancas
    protected $allowIncluded= ['audios','audios.album'];
    protected $allowFilter= ['id','name','description'];
    protected $allowSort= ['id','name'];


    // Relación de uno a muchos con el modelo Audio
    public function audios()
    {
        return $this->hasMany(Audio::class);
    }


   

}
