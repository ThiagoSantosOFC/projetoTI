<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $pattern, callable $handler, array $middlewares = []): void
    {
        $this->routes[] = [
            'method'      => strtoupper($method),
            'pattern'     => $this->patternToRegex($pattern),
            'handler'     => $handler,
            'middlewares' => $middlewares,
            'original'    => $pattern,
        ];
    }

    private function patternToRegex(string $pattern): string
    {
        $pattern = rtrim($pattern, '/');
        if ($pattern === '') {
            $pattern = '/';
        }

        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $regex . '$#';
    }

    /**
     * Register RESTful resource routes for a controller class.
     * GET /base       -> Controller::index
     * GET /base/{id}  -> Controller::show
     * POST /base      -> Controller::store
     * PUT /base/{id}  -> Controller::update
     * DELETE /base/{id} -> Controller::destroy
     */
    public function resource(string $base, string $controllerClass, array $middlewares = []): void
    {
        $base = rtrim($base, '/');

        $this->addRoute('GET',    $base,              [$controllerClass, 'index'],   $middlewares);
        $this->addRoute('GET',    $base . '/{id}',     [$controllerClass, 'show'],    $middlewares);
        $this->addRoute('POST',   $base,               [$controllerClass, 'store'],   $middlewares);
        $this->addRoute('PUT',    $base . '/{id}',     [$controllerClass, 'update'],  $middlewares);
        $this->addRoute('PATCH',  $base . '/{id}',     [$controllerClass, 'patch'],   $middlewares);
        $this->addRoute('DELETE', $base . '/{id}',     [$controllerClass, 'destroy'], $middlewares);
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $path   = $request->getPath();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $request->setParams($params);

            $handler  = $route['handler'];
            $chain    = $route['middlewares'];

            $response = new Response();

            if (!empty($chain)) {
                $next = function (Request $req) use ($handler, $response) {
                    return call_user_func($handler, $req, $response);
                };

                // Build middleware chain (onion pattern)
                $stack = array_reduce(
                    array_reverse($chain),
                    function ($next, $middlewareClass) {
                        return function (Request $req) use ($middlewareClass, $next) {
                            $middleware = new $middlewareClass();
                            return $middleware->handle($req, $next);
                        };
                    },
                    $next
                );

                return $stack($request);
            }

            $result = call_user_func($handler, $request, $response);
            if ($result instanceof Response) {
                return $result;
            }
            return $response;
        }

        $response = new Response();
        $response->json(['error' => 'Rota não encontrada', 'method' => $method, 'path' => $path], 404);
        return $response;
    }
}
