<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@email.com',
            'password' => bcrypt('senha123'),
            'status' => 1,
        ]);
        $user->roles()->attach(1);
    }
}
