<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se é uma requisição POST, PUT, PATCH ou DELETE
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Verificar se o token CSRF está presente
            if (!$request->hasValidSignature() && !$request->hasValidCsrfToken()) {
                return response()->json([
                    'message' => 'Token CSRF inválido ou ausente.',
                    'error' => 'CSRF_TOKEN_MISMATCH'
                ], 419);
            }
        }

        return $next($request);
    }
}

