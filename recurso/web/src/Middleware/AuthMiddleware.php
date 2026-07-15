<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class AuthMiddleware
{
    /**
     * Verify authentication. Returns null to continue, or a Response to short-circuit.
     */
    public function handle(Request $request): ?Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['auth'])) {
            $response = new Response();

            $acceptHeader  = $_SERVER['HTTP_ACCEPT'] ?? '';
            $requestUri    = $_SERVER['REQUEST_URI'] ?? '';

            if (str_contains($acceptHeader, 'application/json') || str_contains($requestUri, '/api/')) {
                return $response->json(['error' => 'Não autenticado'], 401);
            }

            return $response->redirect('/public/views/login.php');
        }

        return null;
    }
}
