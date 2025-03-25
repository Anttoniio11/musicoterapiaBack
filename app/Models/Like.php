<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory, ApiTrait;

    protected $guarded = [];

    // Definir listas blancas
    protected $allowIncluded = ['user', 'likeable']; // Relaciones que se pueden incluir
    protected $allowFilter = ['likeable_type', 'likeable_id', 'user_id']; // Campos que se pueden filtrar
    protected $allowSort = ['created_at', 'updated_at']; // Campos que se pueden ordenar


    // Relación polimórfica 
    public function likeable()
    {
        return $this->morphTo();
    }

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
