<?php

namespace App\Core;

class Auth
{
    private string $usersFile;
    private ?array $usersCache = null;

    public function __construct(string $usersFile)
    {
        $this->usersFile = $usersFile;
    }

    private function loadUsers(): array
    {
        if ($this->usersCache !== null) {
            return $this->usersCache;
        }

        $users = [];
        if (!file_exists($this->usersFile)) {
            $this->usersCache = $users;
            return $users;
        }

        $lines = file($this->usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $users[trim($parts[0])] = trim($parts[1]);
            }
        }

        $this->usersCache = $users;
        return $users;
    }

    public function login(string $username, string $password): bool
    {
        $users = $this->loadUsers();

        if (!isset($users[$username])) {
            return false;
        }

        if (password_verify($password, $users[$username])) {
            session_regenerate_id(true);
            $_SESSION['auth'] = true;
            $_SESSION['username'] = $username;
            return true;
        }

        return false;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function getCurrentUser(): ?string
    {
        return $_SESSION['username'] ?? null;
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
