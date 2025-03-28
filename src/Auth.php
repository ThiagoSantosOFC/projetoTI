<?php
/**
 * Classe Auth - Gerencia a autenticação dos usuários.
 */
class Auth {
    private array $users;

    public function __construct() {
        // Carrega os usuários do arquivo de configuração
        $this->users = require __DIR__ . '/../config/credentials.php';
    }

    /**
     * Realiza o login verificando as credenciais.
     *
     * @param string $username
     * @param string $password
     * @return bool Retorna true se a autenticação for bem-sucedida
     */
    public function login(string $username, string $password): bool {
        // Exemplo simples: verificação direta (ideal utilizar hash e salt em produção)
        return isset($this->users[$username]) && $this->users[$username] === $password;
    }
}
