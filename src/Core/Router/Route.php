<?php
declare(strict_types=1);

namespace App\Core\Router;

class Route
{
    public string $method;
    private string $pattern;
    /** @var callable */
    public $handler;
    public array $params = [];
    private array $middlewares;

    public function __construct(string $method, string $pattern, callable $handler, array $middlewares = [])
    {
        $this->method = strtoupper($method);
        // Armazenar o padrão sem basePath
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->middlewares = $middlewares;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getHandler(): callable
    {
        return $this->handler;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function matches(string $uri): bool
    {
        if ($this->pattern === $uri) {
            return true;
        }

        $patternSegments = explode('/', trim($this->pattern, '/'));
        $uriSegments = explode('/', trim($uri, '/'));

        if (count($patternSegments) !== count($uriSegments)) {
            return false;
        }

        $params = [];

        foreach ($patternSegments as $index => $segment) {
            if (isset($segment[0]) && $segment[0] === ':') {
                $paramName = substr($segment, 1);
                $params[$paramName] = $uriSegments[$index];
                continue;
            }

            if ($segment !== $uriSegments[$index]) {
                return false;
            }
        }

        $this->setParams($params);
        return true;
    }
}
