<?php

namespace App\Core;

class AuthMiddleware
{
    public function handle(Request $request, callable $next): Response
    {
        $auth = new Auth(BASE_PATH . '/data/auth/utilizadores.txt');
        if (!$auth->isAuthenticated()) {
            $response = new Response();
            return $response->json(['error' => 'Não autenticado'], 401);
        }
        return $next($request);
    }
}
