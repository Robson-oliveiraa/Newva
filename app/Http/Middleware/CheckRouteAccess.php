<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $routeName = $request->route()->getName();
        
        // Obter regras de acesso da configuração
        $routePermissions = config('security.route_access', []);

        // Verificar se a rota tem restrições específicas
        foreach ($routePermissions as $pattern => $allowedRoles) {
            if (fnmatch($pattern, $routeName)) {
                $hasPermission = false;
                foreach ($allowedRoles as $role) {
                    if ($user->hasRole($role)) {
                        $hasPermission = true;
                        break;
                    }
                }
                
                if (!$hasPermission) {
                    abort(403, 'Acesso negado. Você não tem permissão para acessar esta funcionalidade.');
                }
                break;
            }
        }

        return $next($request);
    }
}
