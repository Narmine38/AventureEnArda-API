<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création du rôle 'admin'
        Role::create(['name' => 'admin']);

        // Création du rôle 'user'
        Role::create(['name' => 'user']);
    }
}
