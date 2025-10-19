<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasAnyRole
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
        
        // Verifica se o usuário tem pelo menos uma role
        if ($user->roles()->count() === 0) {
            abort(403, 'Acesso negado. Você não possui nenhuma role atribuída. Entre em contato com o administrador.');
        }

        return $next($request);
    }
}

