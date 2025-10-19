<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar permissões específicas do sistema
        $permissions = [
            // Permissões de usuários
            'users.create' => 'Criar usuários',
            'users.read' => 'Visualizar usuários',
            'users.update' => 'Editar usuários',
            'users.delete' => 'Deletar usuários',
            
            // Permissões de consultas
            'consultas.create' => 'Criar consultas',
            'consultas.read' => 'Visualizar consultas',
            'consultas.update' => 'Editar consultas',
            'consultas.delete' => 'Deletar consultas',
            
            // Permissões de carteira de vacina
            'carteira-vacina.create' => 'Criar registros de vacina',
            'carteira-vacina.read' => 'Visualizar carteira de vacina',
            'carteira-vacina.update' => 'Editar carteira de vacina',
            'carteira-vacina.delete' => 'Deletar registros de vacina',
            
            // Permissões de postos de saúde
            'postos-saude.create' => 'Criar postos de saúde',
            'postos-saude.read' => 'Visualizar postos de saúde',
            'postos-saude.update' => 'Editar postos de saúde',
            'postos-saude.delete' => 'Deletar postos de saúde',
            
            // Permissões de médicos
            'medicos.create' => 'Criar médicos',
            'medicos.read' => 'Visualizar médicos',
            'medicos.update' => 'Editar médicos',
            'medicos.delete' => 'Deletar médicos',
            
            // Permissões administrativas
            'admin.access' => 'Acesso ao painel administrativo',
            'admin.dashboard' => 'Visualizar dashboard administrativo',
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::firstOrCreate(
                ['name' => $name],
                [
                    'display_name' => $displayName,
                    'description' => $displayName
                ]
            );
        }

        // Atribuir permissões às roles
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles()
    {
        // Administrador - todas as permissões
        $adminRole = Role::where('name', 'administrator')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }

        // Médico - permissões de consultas e aplicação de vacinas
        $medicoRole = Role::where('name', 'medico')->first();
        if ($medicoRole) {
            $medicoPermissions = Permission::whereIn('name', [
                'consultas.create',
                'consultas.read',
                'consultas.update',
                'consultas.delete',
                'carteira-vacina.create',
                'carteira-vacina.read',
                'carteira-vacina.update',
                'postos-saude.read',
                'medicos.read'
            ])->get();
            $medicoRole->syncPermissions($medicoPermissions);
        }

        // Enfermeiro - permissões de carteira de vacina e visualização
        $enfermeiroRole = Role::where('name', 'enfermeiro')->first();
        if ($enfermeiroRole) {
            $enfermeiroPermissions = Permission::whereIn('name', [
                'consultas.read',
                'carteira-vacina.create',
                'carteira-vacina.read',
                'carteira-vacina.update',
                'postos-saude.read'
            ])->get();
            $enfermeiroRole->syncPermissions($enfermeiroPermissions);
        }

        // Usuário - permissões básicas
        $usuarioRole = Role::where('name', 'usuario')->first();
        if ($usuarioRole) {
            $usuarioPermissions = Permission::whereIn('name', [
                'consultas.create',
                'consultas.read',
                'consultas.update',
                'carteira-vacina.create',
                'carteira-vacina.read',
                'postos-saude.read'
            ])->get();
            $usuarioRole->syncPermissions($usuarioPermissions);
        }
    }
}


