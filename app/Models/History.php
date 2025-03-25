<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $guarded = [];

    //Listas Blancas
    protected $allowIncluded= ['user'];
    protected $allowFilter= ['id'];
    protected $allowSort= ['id'];

    // Relación polimórfica inversa
    public function historable()
    {
        return $this->morphTo();
    }

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
