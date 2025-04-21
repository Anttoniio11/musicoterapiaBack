<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run()
    {
        Genre::create([
            'id' => 1,
            'name' => 'Clásica',
            'description' => 'Música clásica de grandes compositores',
            'image_path' => storage_path('app/public/img/genero-clasica.png'),
        ]);

        Genre::create([
            'id' => 2,
            'name' => 'Ambiental',
            'description' => 'Sonidos naturales y atmósferas relajantes',
            'image_path' => storage_path('app/public/img/genero-ambiental.png'),
        ]);

        Genre::create([
            'id' => 3,
            'name' => 'Instrumental',
            'description' => 'Composiciones sin voces, solo instrumentos',
            'image_path' => storage_path('app/public/img/genero-instrumental.png'),
        ]);

        Genre::create([
            'id' => 4,
            'name' => 'Electrónica',
            'description' => 'Música creada con sintetizadores y beats electrónicos',
            'image_path' => storage_path('app/public/img/genero-electronica.png'),
        ]);
    }
}
