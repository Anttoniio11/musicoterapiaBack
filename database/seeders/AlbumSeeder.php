<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Album;

class AlbumSeeder extends Seeder
{
    public function run()
    {
        Album::create([
            'id' => 1,
            'title' => 'Dormir',
            'description' => 'Música relajante para conciliar el sueño y descansar profundamente',
            'image_path' => storage_path('app/public/img/album-dormir.png'),

        ]);

        Album::create([
            'id' => 2,
            'title' => 'Relajarse',
            'description' => 'Sonidos tranquilos para reducir el estrés y la ansiedad',
            'image_path' => storage_path('app/public/img/album-relajacion.png'),
        ]);

        Album::create([
            'id' => 3,
            'title' => 'Concentrarse',
            'description' => 'Melodías para mejorar el enfoque y la productividad',
            'image_path' => storage_path('app/public/img/album-concentrarse.png'),
        ]);

        Album::create([
            'id' => 4,
            'title' => 'Gamer',
            'description' => 'Música energética para sesiones de gaming intensas',
            'image_path' => storage_path('app/public/img/album-gamer.png'),
        ]);
    }
}
