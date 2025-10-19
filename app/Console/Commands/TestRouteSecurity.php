<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class TestRouteSecurity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:test-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a segurança das rotas do sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔒 Testando Segurança das Rotas...');
        $this->newLine();

        // Listar todas as rotas protegidas
        $protectedRoutes = $this->getProtectedRoutes();
        
        $this->info('📋 Rotas Protegidas Encontradas:');
        foreach ($protectedRoutes as $route) {
            $this->line("  • {$route['method']} {$route['uri']} - {$route['name']}");
        }
        
        $this->newLine();
        
        // Testar roles e permissões
        $this->testRolesAndPermissions();
        
        $this->newLine();
        $this->info('✅ Teste de segurança concluído!');
    }

    private function getProtectedRoutes()
    {
        $routes = Route::getRoutes();
        $protectedRoutes = [];

        foreach ($routes as $route) {
            $middleware = $route->gatherMiddleware();
            
            if (in_array('auth', $middleware) || in_array('has.any.role', $middleware)) {
                $protectedRoutes[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName() ?? 'N/A',
                    'middleware' => $middleware
                ];
            }
        }

        return $protectedRoutes;
    }

    private function testRolesAndPermissions()
    {
        $this->info('👥 Testando Roles e Permissões:');
        
        $roles = Role::with('permissions')->get();
        
        foreach ($roles as $role) {
            $this->line("  • {$role->name}:");
            $permissions = $role->permissions->pluck('name')->toArray();
            foreach ($permissions as $permission) {
                $this->line("    - {$permission}");
            }
        }
        
        $this->newLine();
        
        // Verificar usuários sem roles
        $usersWithoutRoles = User::doesntHave('roles')->count();
        if ($usersWithoutRoles > 0) {
            $this->warn("⚠️  {$usersWithoutRoles} usuário(s) sem roles atribuídas!");
        } else {
            $this->info("✅ Todos os usuários possuem roles atribuídas.");
        }
    }
}
