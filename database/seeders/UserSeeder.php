<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Usuario',
            'birthdate' => '1990-01-01',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now()
        ]);

        $this->command->info('Usuario creado exitosamente!');
        $this->command->info('Email: admin@mail.com');
        $this->command->info('Password: 123456');
    }
}
