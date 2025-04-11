<?php
// src/Infrastructure/Router/Router.php
declare(strict_types=1);

namespace App\Infrastructure\Router;

use App\api\ResponseFactory;
use App\Core\Router\Route;
use App\Core\Router\RouterInterface;

class Router implements RouterInterface
{
    /** @var Route[] */
    private array $routes = [];
    public string $basePath = '' {
        set {
            $this->basePath = rtrim($value, '/');
        }
    }

    /**
     * Adiciona uma rota.
     *
     * @param string   $method   Método HTTP (GET, POST, PUT, DELETE, etc.)
     * @param string   $pattern  Padrão da rota (ex: '/sensors/:id')
     * @param callable $handler  Função callback que trata a requisição
     */
    public function addRoute(string $method, string $pattern, callable $handler): void
    {
        $pattern = $this->basePath . '/' . ltrim($pattern, '/');
        $this->routes[] = new Route($method, $pattern, $handler);
    }

    /**
     * Métodos de conveniência para rotas comuns
     */
    public function get(string $pattern, callable $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    public function put(string $pattern, callable $handler): void
    {
        $this->addRoute('PUT', $pattern, $handler);
    }

    public function patch(string $pattern, callable $handler): void
    {
        $this->addRoute('PATCH', $pattern, $handler);
    }

    public function delete(string $pattern, callable $handler): void
    {
        $this->addRoute('DELETE', $pattern, $handler);
    }

    /**
     * Resolve uma requisição HTTP para o handler adequado.
     *
     * @param string $method    Método HTTP da requisição
     * @param string $uri       URI da requisição
     */
    public function resolve(string $method, string $uri): void
    {
        $method = strtoupper($method);

        // Remove query strings
        $uri = parse_url($uri, PHP_URL_PATH) ?? $uri;

        foreach ($this->routes as $route) {
            if ($route->method === $method && $route->matches($uri)) {
                $this->handleRoute($route);
                return;
            }
        }

        // Nenhuma rota encontrada
        $this->handleNotFound();
    }

    /**
     * Processa a rota encontrada
     */
    private function handleRoute(Route $route): void
    {
        try {
            $handler = $route->handler;
            $params = $route->params;

            call_user_func($handler, $params);
        } catch (\Throwable $e) {
            ResponseFactory::error('Erro do servidor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Responde com 404 quando nenhuma rota for encontrada
     */
    private function handleNotFound(): void
    {
        ResponseFactory::notFound('Rota não encontrada');
    }
    public function addRouteWithMiddleware(
        string $method,
        string $pattern,
        callable $handler,
        array $middlewares = []
    ): void {
        $pattern = $this->basePath . '/' . ltrim($pattern, '/');

        // Cria uma função de handler que executa os middlewares em ordem
        $wrappedHandler = function($params) use ($handler, $middlewares) {
            $next = $handler;

            // Construir a pilha de middlewares (do último para o primeiro)
            foreach (array_reverse($middlewares) as $middleware) {
                $currentNext = $next;
                $next = function($p) use ($middleware, $currentNext) {
                    $middleware->process($currentNext, $p);
                };
            }

            // Executar a pilha de middlewares
            $next($params);
        };

        $this->routes[] = new Route($method, $pattern, $wrappedHandler);
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
    public function setBasePath(string $basePath): void
    {
        $this->basePath = rtrim($basePath, '/');
    }
}