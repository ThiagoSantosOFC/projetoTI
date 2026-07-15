<?php

namespace App\Lib;

class FileStore
{
    public static function readFile(string $path): string
    {
        if (!file_exists($path)) {
            return '';
        }
        return trim(file_get_contents($path));
    }

    public static function writeFile(string $path, string $content): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $fp = fopen($path, 'w');
        if ($fp === false) {
            throw new \RuntimeException("Não foi possível abrir o ficheiro: $path");
        }

        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $content);
            fflush($fp);
            flock($fp, LOCK_UN);
        }

        fclose($fp);
    }

    public static function appendFile(string $path, string $line): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $fp = fopen($path, 'a');
        if ($fp === false) {
            throw new \RuntimeException("Não foi possível abrir o ficheiro: $path");
        }

        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $line . "\n");
            fflush($fp);
            flock($fp, LOCK_UN);
        }

        fclose($fp);
    }

    public static function deleteFile(string $path): bool
    {
        if (!file_exists($path)) {
            return false;
        }
        return unlink($path);
    }

    public static function ensureDir(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}
