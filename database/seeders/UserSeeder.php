<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin (se não existir)
        $admin = User::firstOrCreate(
            ['email' => 'admin@newva.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'cpf' => '000.000.000-00',
                'sexo' => 'M',
                'idade' => 30,
            ]
        );
        
        if (!$admin->hasRole('administrator')) {
            $admin->addRole('administrator');
        }

        // Criar usuários comuns
        $users = [
            [
                'name' => 'João Silva',
                'email' => 'joao@example.com',
                'password' => Hash::make('password'),
                'cpf' => '111.111.111-11',
                'sexo' => 'M',
                'idade' => 25,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'cpf' => '222.222.222-22',
                'sexo' => 'F',
                'idade' => 30,
            ],
            [
                'name' => 'Pedro Oliveira',
                'email' => 'pedro@example.com',
                'password' => Hash::make('password'),
                'cpf' => '333.333.333-33',
                'sexo' => 'M',
                'idade' => 28,
            ],
            [
                'name' => 'Ana Costa',
                'email' => 'ana@example.com',
                'password' => Hash::make('password'),
                'cpf' => '444.444.444-44',
                'sexo' => 'F',
                'idade' => 35,
            ],
            [
                'name' => 'Carlos Ferreira',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password'),
                'cpf' => '555.555.555-55',
                'sexo' => 'M',
                'idade' => 42,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->addRole('usuario');
        }
    }
}
