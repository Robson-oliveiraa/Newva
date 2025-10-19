<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignRoleToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-role {email} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atribui uma role a um usuário específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $roleName = $this->argument('role');

        // Buscar o usuário
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("Usuário com email '{$email}' não encontrado.");
            return 1;
        }

        // Buscar a role
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' não encontrada.");
            $this->info("Roles disponíveis: " . Role::pluck('name')->implode(', '));
            return 1;
        }

        // Verificar se o usuário já tem a role
        if ($user->hasRole($roleName)) {
            $this->warn("Usuário '{$email}' já possui a role '{$roleName}'.");
            return 0;
        }

        // Atribuir a role
        $user->addRole($role);
        $this->info("Role '{$roleName}' atribuída com sucesso ao usuário '{$email}'.");

        return 0;
    }
}
