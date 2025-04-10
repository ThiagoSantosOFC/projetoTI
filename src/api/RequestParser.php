<?php
// src/Api/RequestParser.php
declare(strict_types=1);

namespace App\Api;

class RequestParser
{
    public static function getJsonBody(): array
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            ResponseFactory::error('JSON inválido: ' . json_last_error_msg(), 400);
        }

        return $data ?? [];
    }

    public static function getPathSegments(): array
    {
        $path = $_SERVER['PATH_INFO'] ?? '';
        $path = trim($path, '/');
        return $path ? explode('/', $path) : [];
    }

    public static function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getQueryParams(): array
    {
        return $_GET;
    }
}