<?php

namespace App\Core;

class Request
{
    private array $params = [];

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
            $body = file_get_contents('php://input');
            if (!empty($body)) {
                $json = json_decode($body, true);
                if (is_array($json)) {
                    $_POST = $json;
                }
            }
        }
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $this->params[$key] ?? $default;
    }

    public function getQueryParam(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getFile(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}
