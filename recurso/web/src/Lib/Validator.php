<?php

namespace App\Lib;

class Validator
{
    public static function sanitize(string $input): string
    {
        return trim(strip_tags($input));
    }

    public static function validateId(string $id): bool
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $id) === 1;
    }

    public static function validateTipo(string $tipo): bool
    {
        return in_array($tipo, ['sensor', 'actuator'], true);
    }

    public static function validateFile(array $file): array
    {
        $errors = [];

        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $errors[] = 'Nenhum ficheiro enviado.';
            return $errors;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erro no upload do ficheiro.';
            return $errors;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            $errors[] = 'Extensão não permitida. Apenas .jpg e .png.';
        }

        if ($file['size'] > 1048576) {
            $errors[] = 'Ficheiro demasiado grande. Máximo 1MB.';
        }

        return $errors;
    }
}
