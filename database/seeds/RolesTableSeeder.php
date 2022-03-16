<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'Administrador', 'slug' => 'adm']);
        Role::create(['name' => 'Usuário', 'slug' => 'usr']);
    }
}
