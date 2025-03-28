<?php
/**
 * Classe Router - responsável por roteamento simples de requisições.
 * Segue os padrões e naming conventions recomendados.
 */
class Router {
    // Armazena as rotas definidas
    private array $routes = [];

    /**
     * Adiciona uma rota à API.
     *
     * @param string   $method   Método HTTP (GET, POST, etc.)
     * @param string   $uri      URI da rota
     * @param callable $handler  Função callback que trata a requisição
     */
    public function addRoute(string $method, string $uri, callable $handler): void {
        $this->routes[$method][$uri] = $handler;
    }

    /**
     * Roteia a requisição para o handler adequado.
     *
     * @param string $method
     * @param string $requestUri
     */
    public function route(string $method, string $requestUri): void {
        // Remove query strings, se houver
        $uri = parse_url($requestUri, PHP_URL_PATH);
        if (isset($this->routes[$method][$uri])) {
            // Executa a função associada à rota
            call_user_func($this->routes[$method][$uri]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Rota não encontrada']);
        }
    }
}
