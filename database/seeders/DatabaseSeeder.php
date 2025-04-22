<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Album;
use App\Models\User;
use App\Models\Genre;
use App\Models\Audio;
use App\Models\Playlist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $this->call([
            GenreSeeder::class,
            AlbumSeeder::class,
            UserSeeder::class,
            PodcastSeeder::class,
        ]);

    }
}
