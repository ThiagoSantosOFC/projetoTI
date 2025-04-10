<?php
declare(strict_types=1);

namespace App\Core\Router;

class Route
{
    public string $method {
        get {
            return $this->method;
        }
    }
    private string $pattern {
        get {
            return $this->pattern;
        }
    }
    /** @var callable */
    public $handler {
        get {
            return $this->handler;
        }
    }
    public array $params = [] {
        get {
            return $this->params;
        }
        set {
            $this->params = $value;
        }
    }
    private array $middlewares {
        get {
            return $this->middlewares;
        }
    }

    public function __construct(string $method, string $pattern, callable $handler, array $middlewares = [])
    {
        $this->method = strtoupper($method);
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->middlewares = $middlewares;
    }

    public function matches(string $uri): bool
    {
        // Pattern exato
        if ($this->pattern === $uri) {
            return true;
        }

        // Pattern com parâmetros
        $patternSegments = explode('/', trim($this->pattern, '/'));
        $uriSegments = explode('/', trim($uri, '/'));

        if (count($patternSegments) !== count($uriSegments)) {
            return false;
        }

        $params = [];

        foreach ($patternSegments as $index => $segment) {
            // Verificar se é um parâmetro (começa com :)
            if (isset($segment[0]) && $segment[0] === ':') {
                $paramName = substr($segment, 1);
                $params[$paramName] = $uriSegments[$index];
                continue;
            }

            // Segmentos estáticos devem corresponder exatamente
            if ($segment !== $uriSegments[$index]) {
                return false;
            }
        }

        $this->params = $params;
        return true;
    }
}