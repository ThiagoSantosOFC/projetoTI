<?php

namespace App\Lib;

class ImageHandler
{
    public static function processUpload(array $file, string $destDir): ?string
    {
        $errors = Validator::validateFile($file);
        if (!empty($errors)) {
            return null;
        }

        FileStore::ensureDir($destDir);

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = date('Ymd_His') . '_' . uniqid() . '.' . $extension;
        $destPath = $destDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return $destPath;
        }

        return null;
    }

    public static function getLastImage(string $dir): ?string
    {
        if (!is_dir($dir)) {
            return null;
        }

        $files = glob($dir . '/*.{jpg,jpeg,png}', GLOB_BRACE);
        if (empty($files)) {
            return null;
        }

        usort($files, function (string $a, string $b): int {
            return filemtime($b) - filemtime($a);
        });

        return $files[0];
    }

    public static function listImages(string $dir): array
    {
        if (!is_dir($dir)) {
            return [];
        }

        $files = glob($dir . '/*.{jpg,jpeg,png}', GLOB_BRACE);

        usort($files, function (string $a, string $b): int {
            return filemtime($b) - filemtime($a);
        });

        $images = [];
        foreach ($files as $file) {
            $images[] = [
                'path'  => $file,
                'name'  => basename($file),
                'hora'  => date('Y-m-d H:i:s', filemtime($file)),
                'size'  => filesize($file),
            ];
        }

        return $images;
    }
}
