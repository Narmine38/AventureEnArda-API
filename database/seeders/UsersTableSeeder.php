<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Création de l'utilisateur 'admin'
        $admin = User::create([
            'name' => 'Admin Name',  // Remplacez par le nom souhaité
            'email' => 'admin@example.com',  // Remplacez par l'email souhaité
            'password' => Hash::make('password')  // Utilisation de Hash pour le mot de passe
        ]);
        $admin->assignRole('admin');  // Attribuer le rôle 'admin' à cet utilisateur

        // Création de l'utilisateur 'user'
        $user = User::create([
            'name' => 'User Name',  // Remplacez par le nom souhaité
            'email' => 'user@example.com',  // Remplacez par l'email souhaité
            'password' => Hash::make('password')  // Utilisation de Hash pour le mot de passe
        ]);
        $user->assignRole('user');  // Attribuer le rôle 'user' à cet utilisateur
    }
}
