<?php
/**
 * Teste unitário para a classe Router.
 * Execute com: php tests/RouterTest.php
 */

require_once __DIR__ . '/../src/Router.php';

echo "Iniciando testes para Router...\n";

// Cria uma instância do Router
$router = new Router();

// Define uma rota de teste
$router->addRoute('GET', '/teste', function() {
    echo 'Teste OK';
});

// Simula uma requisição para a rota definida
ob_start();
$router->route('GET', '/teste');
$output = ob_get_clean();

// Verifica se o output é o esperado
assert($output === 'Teste OK', 'Rota /teste  não funcionou corretamente.');

echo "Todos os testes passaram!\n";
