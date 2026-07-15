<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(Request $req): Response
    {
        $username = $req->getParam('username', '');
        $password = $req->getParam('password', '');

        if ($username === '' || $password === '') {
            return (new Response())->json([
                'success' => false,
                'message' => 'Username e password são obrigatórios.',
            ], 400);
        }

        if ($this->authService->login($username, $password)) {
            return (new Response())->json([
                'success' => true,
                'message' => 'Login efetuado com sucesso.',
                'user'    => $username,
            ]);
        }

        return (new Response())->json([
            'success' => false,
            'message' => 'Credenciais inválidas.',
        ], 401);
    }

    public function logout(Request $req): Response
    {
        $this->authService->logout();

        return (new Response())->json([
            'success' => true,
            'message' => 'Sessão terminada.',
        ]);
    }

    public function session(Request $req): Response
    {
        $authenticated = $this->authService->isAuthenticated();
        $user          = $this->authService->getCurrentUser();

        return (new Response())->json([
            'authenticated' => $authenticated,
            'user'          => $user,
        ]);
    }
}
