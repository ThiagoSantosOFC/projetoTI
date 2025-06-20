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
    private string $basePath = '';

    public function setBasePath(string $basePath): void
    {
        // Remove barra final se houver
        $this->basePath = rtrim($basePath, '/');
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Adiciona uma rota sem embutir basePath
     */
    public function addRoute(string $method, string $pattern, callable $handler): void
    {
        // Normalizar padrão da rota, removendo barra duplicada
        $pattern = '/' . trim($pattern, '/');
        $this->routes[] = new Route($method, $pattern, $handler);
    }

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

    public function addRouteWithMiddleware(string $method, string $pattern, callable $handler, array $middlewares = []): void
    {
        $pattern = '/' . trim($pattern, '/');

        $wrappedHandler = function($params) use ($handler, $middlewares) {
            $next = $handler;

            foreach (array_reverse($middlewares) as $middleware) {
                $currentNext = $next;
                $next = function($p) use ($middleware, $currentNext) {
                    $middleware->process($currentNext, $p);
                };
            }

            $next($params);
        };

        $this->routes[] = new Route($method, $pattern, $wrappedHandler, $middlewares);
    }

    /**
     * Resolve a rota com debug para visualizar rotas e URI
     */
    public function resolve(string $method, string $uri): void
    {
        // Remove basePath do início da URI para casar com padrões
        $basePath = $this->basePath;

        if (!empty($basePath) && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Normaliza URI
        $uri = $uri === '' ? '/' : $uri;
        $uri = '/' . trim($uri, '/');

        // --- DEBUG: listar rotas e URI para ajudar no diagnóstico ---
        echo "<pre>=== Rotas Registradas ===\n";
        foreach ($this->routes as $route) {
            echo $route->getMethod() . ' ' . $route->getPattern() . "\n";
        }
        echo "=== URI Recebida ===\n";
        echo $method . ' ' . $uri . "\n";
        echo "=====================\n";
        echo "</pre>";
        // exit; // descomente para parar aqui e analisar

        foreach ($this->routes as $route) {
            if ($route->getMethod() === $method && $route->matches($uri)) {
                $handler = $route->getHandler();
                $middlewares = $route->getMiddlewares();

                $this->executeMiddlewares($middlewares, $handler, $route->getParams());
                return;
            }
        }

        // Se nenhuma rota bateu
        http_response_code(404);
        echo "Rota não encontrada";
    }

    /**
     * Executa a cadeia de middlewares e depois o handler final
     */
    private function executeMiddlewares(array $middlewares, callable $handler, array $params): void
    {
        $next = $handler;

        foreach (array_reverse($middlewares) as $middleware) {
            $currentNext = $next;
            $next = function($p) use ($middleware, $currentNext) {
                $middleware->process($currentNext, $p);
            };
        }

        $next($params);
    }
}
