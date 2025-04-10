<?php
// src/Api/ResponseFactory.php
declare(strict_types=1);

namespace App\Api;

class ResponseFactory
{
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    public static function error(string $message, int $statusCode = 400): void
    {
        self::json(['error' => $message], $statusCode);
    }

    public static function notFound(string $message = 'Recurso não encontrado'): void
    {
        self::error($message, 404);
    }
}