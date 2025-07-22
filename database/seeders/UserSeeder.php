<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()
            ->create([
                'email' => config('app.default_user.email'),
                'password' => Hash::make(config('app.default_user.password')),
                'name' => config('app.default_user.name'),
            ]);

        // Crea il primo autore associato all'utente
        \App\Models\Author::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Biografia predefinita',
            'settings' => null,
        ]);
    }
}
