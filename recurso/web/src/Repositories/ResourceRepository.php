<?php

namespace App\Repositories;

use App\Models\Resource;
use App\Lib\FileStore;

class ResourceRepository
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = dirname(__DIR__, 2) . '/data/resources';
    }

    /**
     * Scan data/resources/ for all resource folders, read each, return Resources array.
     * AUTO-DESCOBERTA — any folder = a resource.
     */
    public function findAll(): array
    {
        $resources = [];
        foreach ($this->getAllIds() as $id) {
            $r = $this->findById($id);
            if ($r !== null) {
                $resources[] = $r;
            }
        }
        return $resources;
    }

    public function findById(string $id): ?Resource
    {
        $dir = $this->basePath . '/' . $id;
        if (!is_dir($dir)) {
            return null;
        }

        $data = ['id' => $id];
        foreach (['nome', 'tipo', 'valor', 'unidade', 'hora'] as $f) {
            $data[$f] = FileStore::readFile($dir . '/' . $f . '.txt');
        }
        return Resource::fromArray($data);
    }

    public function create(string $id, string $nome, string $tipo, string $unidade): bool
    {
        $dir = $this->basePath . '/' . $id;
        if (is_dir($dir)) {
            return false;
        }

        FileStore::ensureDir($dir);
        FileStore::writeFile($dir . '/nome.txt', $nome);
        FileStore::writeFile($dir . '/tipo.txt', $tipo);
        FileStore::writeFile($dir . '/unidade.txt', $unidade);
        FileStore::writeFile($dir . '/valor.txt', '');
        FileStore::writeFile($dir . '/hora.txt', '');
        FileStore::writeFile($dir . '/log.txt', '');

        return true;
    }

    public function updateValue(string $id, string $valor): bool
    {
        $dir = $this->basePath . '/' . $id;
        if (!is_dir($dir)) {
            return false;
        }

        $hora = date('Y-m-d H:i:s');
        FileStore::writeFile($dir . '/valor.txt', $valor);
        FileStore::writeFile($dir . '/hora.txt', $hora);
        $this->appendLog($id, $valor);

        return true;
    }

    public function updateConfig(string $id, array $data): bool
    {
        $dir = $this->basePath . '/' . $id;
        if (!is_dir($dir)) {
            return false;
        }

        foreach (['nome', 'tipo', 'unidade'] as $field) {
            if (isset($data[$field])) {
                FileStore::writeFile($dir . '/' . $field . '.txt', $data[$field]);
            }
        }
        return true;
    }

    public function delete(string $id): bool
    {
        $dir = $this->basePath . '/' . $id;
        if (!is_dir($dir)) {
            return false;
        }
        $this->rrmdir($dir);
        return true;
    }

    public function getHistorico(string $id, int $limite = 20): array
    {
        $path = $this->getLogPath($id);
        if (!file_exists($path)) {
            return [];
        }

        $content = FileStore::readFile($path);
        if ($content === '') {
            return [];
        }

        $lines = explode("\n", $content);
        $lines = array_values(array_filter($lines, fn($l) => trim($l) !== ''));
        $lines = array_slice($lines, -$limite);

        return array_map(function (string $line): array {
            $parts = explode('|', $line);
            return [
                'hora'   => $parts[0] ?? '',
                'valor'  => $parts[1] ?? '',
                'status' => $parts[2] ?? 'OK',
            ];
        }, $lines);
    }

    public function clearLog(string $id): bool
    {
        $path = $this->getLogPath($id);
        if (!file_exists($path)) {
            return false;
        }
        FileStore::writeFile($path, '');
        return true;
    }

    public function appendLog(string $id, string $valor, string $status = 'OK'): void
    {
        $line = date('Y-m-d H:i:s') . '|' . $valor . '|' . $status;
        FileStore::appendFile($this->getLogPath($id), $line);
    }

    public function getLogPath(string $id): string
    {
        return $this->basePath . '/' . $id . '/log.txt';
    }

    public function getAllIds(): array
    {
        if (!is_dir($this->basePath)) {
            return [];
        }

        $ids = [];
        foreach (scandir($this->basePath) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (is_dir($this->basePath . '/' . $item)) {
                $ids[] = $item;
            }
        }
        sort($ids);
        return $ids;
    }

    /** Recursively remove a directory. */
    private function rrmdir(string $dir): void
    {
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->rrmdir($path) : unlink($path);
        }
        rmdir($dir);
    }
}
