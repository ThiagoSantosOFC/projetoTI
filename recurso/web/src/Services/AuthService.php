<?php

namespace App\Services;

use App\Lib\FileStore;

class AuthService
{
    private string $usersFile;

    public function __construct()
    {
        $this->usersFile = dirname(__DIR__, 2) . '/data/auth/users.txt';
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(string $username, string $password): bool
    {
        $username = trim($username);
        $users = $this->loadUsers();

        if (isset($users[$username]) && password_verify($password, $users[$username])) {
            $_SESSION['auth']       = true;
            $_SESSION['user']       = $username;
            $_SESSION['login_time'] = time();
            return true;
        }

        return false;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    public function isAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['auth']);
    }

    public function getCurrentUser(): ?string
    {
        return $this->isAuthenticated() ? ($_SESSION['user'] ?? null) : null;
    }

    /** Load users.txt into [username => password_hash] map. */
    private function loadUsers(): array
    {
        if (!file_exists($this->usersFile)) {
            return [];
        }

        $content = FileStore::readFile($this->usersFile);
        if ($content === '') {
            return [];
        }

        $users = [];
        foreach (explode("\n", $content) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $users[$parts[0]] = $parts[1];
            }
        }

        return $users;
    }
}
