<?php

namespace App\Core;

class Response
{
    private int $statusCode = 200;

    public function json(mixed $data, int $status = 200): static
    {
        $this->statusCode = $status;
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this;
    }

    public function redirect(string $url): static
    {
        header('Location: ' . $url);
        return $this;
    }

    public function setStatusCode(int $code): static
    {
        $this->statusCode = $code;
        http_response_code($code);
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
