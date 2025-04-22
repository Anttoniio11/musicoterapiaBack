<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Podcast;

class PodcastSeeder extends Seeder
{
    public function run()
    {
        $podcasts = [
            [
                'title' => 'Dormir',
                'description' => 'Música relajante para conciliar el sueño y descansar profundamente',
                'image_file' => 'img/album-dormir.png',
                'image_public_id' => 'podcasts/album-dormir',
                'video_file' => 'videos/dormir.mp4',
                'video_public_id' => 'podcasts/dormir',
                'duration' => 60, // 10 minutos
            ],
            [
                'title' => 'Relajarse',
                'description' => 'Sonidos tranquilos para reducir el estrés y la ansiedad',
                'image_file' => 'img/album-relajacion.png',
                'image_public_id' => 'podcasts/album-relajacion',
                'video_file' => 'videos/relajarse.mp4',
                'video_public_id' => 'podcasts/relajarse',
                'duration' => 48, // 8 minutos
            ],
            [
                'title' => 'Concentrarse',
                'description' => 'Melodías para mejorar el enfoque y la productividad',
                'image_file' => 'img/album-concentrarse.png',
                'image_public_id' => 'podcasts/album-concentrarse',
                'video_file' => 'videos/concentrarse.mp4',
                'video_public_id' => 'podcasts/concentrarse',
                'duration' => 72, // 12 minutos
            ],
            [
                'title' => 'Gamer',
                'description' => 'Música energética para sesiones de gaming intensas',
                'image_file' => 'img/album-gamer.png',
                'image_public_id' => 'podcasts/album-gamer',
                'video_file' => 'videos/gamer.mp4',
                'video_public_id' => 'podcasts/gamer',
                'duration' => 34, // 15 minutos
            ],
        ];

        foreach ($podcasts as $podcast) {
            Podcast::create($podcast);
        }
    }
}
